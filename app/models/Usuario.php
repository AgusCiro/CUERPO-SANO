<?php
// app/models/Usuario.php
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

            $sql = "SELECT * FROM miembros WHERE DNI = :dni LIMIT 1";
            $stmt = $this->conPDO->prepare($sql);
            $stmt->bindValue(':dni', $dni);
            $stmt->execute();

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$usuario) {
                return "No se encontró usuario con DNI: {$dni}";
            }

            $hash = $usuario['codigo_barcode'] ?? '';

            if ($hash === '' || $hash === null) {
                return "El usuario no tiene contraseña guardada (codigo_barcode vacío).";
            }

            // comprobar longitud del hash (debug)
            $len = strlen($hash);
            if ($len < 50) {
                return "El hash almacenado parece truncado (longitud: {$len}).";
            }

            if (!password_verify($password, $hash)) {
                return "Contraseña incorrecta.";
            }

            // credenciales válidas -> crear sesión
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
}
?>
