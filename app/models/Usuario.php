
<?php

include_once __DIR__ . '/conexion.php';

class Usuario {
    private $conPDO;

    public function __construct() {
        $this->conPDO = conectar();
        if ($this->conPDO instanceof PDO) {
            $this->conPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // forzar utf8 (si tu conexión ya lo hace, no hace daño)
            $this->conPDO->exec("SET NAMES 'utf8'");
        }
    }

    /**
     * Devuelve true si login OK, o string con mensaje de error (útil para debug).
     */
    public function login($dni, $password) {
        try {
            $dni = trim($dni);

            // Verificar si el usuario está bloqueado
            $bloqueo = $this->verificarBloqueo($dni);
            if ($bloqueo !== false) {
                return "Usuario bloqueado. Intente nuevamente en {$bloqueo} segundos.";
            }

            $sql = "SELECT * FROM miembros WHERE DNI = :dni LIMIT 1";
            $stmt = $this->conPDO->prepare($sql);
            $stmt->bindValue(':dni', $dni);
            $stmt->execute();

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$usuario) {
                $this->registrarIntentoFallido($dni);
                return "No se encontró usuario con DNI: {$dni}";
            }

            $hash = $usuario['password'] ?? '';

            if ($hash === '' || $hash === null) {
                $this->registrarIntentoFallido($dni);
                return "El usuario no tiene contraseña guardada (password vacío).";
            }

            // comprobar longitud del hash (debug)
            $len = strlen($hash);
            if ($len < 50) {
                $this->registrarIntentoFallido($dni);
                return "El hash almacenado parece truncado (longitud: {$len}).";
            }

            if (!password_verify($password, $hash)) {
                $intentos = $this->registrarIntentoFallido($dni);
                if ($intentos >= 3) {
                    return "Demasiados intentos fallidos. Usuario bloqueado por 1 minuto.";
                }
                return "Contraseña incorrecta. Intentos restantes: " . (3 - $intentos);
            }

            // credenciales válidas -> resetear intentos y crear sesión
            $this->resetearIntentos($dni);
            if (session_status() !== PHP_SESSION_ACTIVE) session_start();
            $_SESSION['USUARIO'] = [
                'id' => $usuario['id'] ?? null,
                'nombre' => $usuario['nombre'] ?? '',
                'apellido' => $usuario['apellido'] ?? '',
                'email' => $usuario['email'] ?? '',
                'rol' => $usuario['rol'] ?? ''
            ];
            $_SESSION['timeout'] = time();

            return true;
        } catch (PDOException $e) {
            return "Error de base de datos: " . $e->getMessage();
        } catch (Exception $e) {
            return "Error inesperado: " . $e->getMessage();
        }
    }

    public function logout() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        session_unset();
        session_destroy();
    }

    public static function requireRol($rolPermitido) {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (!isset($_SESSION['USUARIO']) || $_SESSION['USUARIO']['rol'] != $rolPermitido) {
            die("No tiene permisos para acceder a esta sección.");
        }
    }
    
    public function crearUsuario($nombre, $apellido, $email, $dni, $password, $rol) {
    try {
        $sql = "INSERT INTO miembros (nombre, apellido, email, dni, password, rol)
                VALUES (:nombre, :apellido, :email, :dni, :password, :rol)";
        $stmt = $this->conPDO->prepare($sql);

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':dni', $dni);
        $stmt->bindParam(':password', $passwordHash);
        $stmt->bindParam(':rol', $rol);

        return $stmt->execute();
    } catch (PDOException $e) {
        echo "Error al crear usuario: " . $e->getMessage();
        return false;
        }
    }


    // 🔹 Validar fortaleza de contraseña
    public function validarFortalezaPassword($password) {
        $errores = [];
        
        if (strlen($password) < 8) {
            $errores[] = "La contraseña debe tener al menos 8 caracteres";
        }
        
        if (!preg_match('/[A-Z]/', $password)) {
            $errores[] = "La contraseña debe contener al menos una letra mayúscula";
        }
        
        if (!preg_match('/[a-z]/', $password)) {
            $errores[] = "La contraseña debe contener al menos una letra minúscula";
        }
        
        if (!preg_match('/[0-9]/', $password)) {
            $errores[] = "La contraseña debe contener al menos un número";
        }
        
        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            $errores[] = "La contraseña debe contener al menos un carácter especial";
        }
        
        return $errores;
    }

    // 🔹 Verificar si DNI ya existe
    public function dniExiste($dni) {
        try {
            $sql = "SELECT COUNT(*) FROM miembros WHERE DNI = :dni";
            $stmt = $this->conPDO->prepare($sql);
            $stmt->bindParam(':dni', $dni);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Error dniExiste: " . $e->getMessage());
            return false;
        }
    }

    // 🔹 Sistema de intentos de login
    public function registrarIntentoFallido($dni) {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        
        $key = "intentos_login_" . $dni;
        $timestamp_key = "timestamp_login_" . $dni;
        
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = 0;
        }
        
        $_SESSION[$key]++;
        $_SESSION[$timestamp_key] = time();
        
        return $_SESSION[$key];
    }

    public function verificarBloqueo($dni) {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        
        $key = "intentos_login_" . $dni;
        $timestamp_key = "timestamp_login_" . $dni;
        
        if (!isset($_SESSION[$key]) || $_SESSION[$key] < 3) {
            return false; // No está bloqueado
        }
        
        $ultimo_intento = $_SESSION[$timestamp_key] ?? 0;
        $tiempo_espera = 60; // 1 minuto
        
        if ((time() - $ultimo_intento) < $tiempo_espera) {
            $tiempo_restante = $tiempo_espera - (time() - $ultimo_intento);
            return $tiempo_restante; // Devuelve segundos restantes
        } else {
            // Tiempo de bloqueo expirado, resetear intentos
            unset($_SESSION[$key]);
            unset($_SESSION[$timestamp_key]);
            return false;
        }
    }

    public function resetearIntentos($dni) {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        
        $key = "intentos_login_" . $dni;
        $timestamp_key = "timestamp_login_" . $dni;
        
        unset($_SESSION[$key]);
        unset($_SESSION[$timestamp_key]);
    }

    // 🔹 Cambiar contraseña
    public function cambiarContrasena($dni, $nueva_password) {
        try {
            $sql = "UPDATE miembros SET password = :password WHERE DNI = :dni";
            $stmt = $this->conPDO->prepare($sql);
            $stmt->bindParam(':password', password_hash($nueva_password, PASSWORD_DEFAULT));
            $stmt->bindParam(':dni', $dni);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error cambiarContrasena: " . $e->getMessage());
            return false;
        }
    }
}
?>
