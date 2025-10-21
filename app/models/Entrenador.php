<?php

include_once __DIR__ . '/conexion.php';

class Entrenador {
    private $conPDO;

    public function __construct() {
        $this->conPDO = conectar();
        if ($this->conPDO instanceof PDO) {
            $this->conPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conPDO->exec("SET NAMES 'utf8'");
        }
    }

    /**
     * Obtener todos los entrenadores
     */
    public function obtenerEntrenadores($filtro = '') {
        try {
            $sql = "SELECT e.* 
                    FROM entrenadores e";
            if (!empty($filtro)) {
                $sql .= " WHERE e.nombre LIKE :filtro 
                         OR e.apellido LIKE :filtro 
                         OR e.dni LIKE :filtro
                         OR e.email LIKE :filtro
                         OR e.numero_entrenador LIKE :filtro
                         OR e.codigo_barcode LIKE :filtro";
            }
            
            $sql .= " ORDER BY e.apellido, e.nombre";
            
            $stmt = $this->conPDO->prepare($sql);
            
            if (!empty($filtro)) {
                $filtroParam = "%{$filtro}%";
                $stmt->bindParam(':filtro', $filtroParam);
            }
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerEntrenadores: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener un entrenador por ID
     */
    public function obtenerEntrenadorPorId($id) {
        try {
            $sql = "SELECT e.* 
                    FROM entrenadores e 
                    WHERE e.id = :id";
            
            $stmt = $this->conPDO->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $entrenador = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($entrenador) {
                $entrenador['tipos'] = $this->obtenerTiposDeEntrenador($id);
            }
            
            return $entrenador;
        } catch (PDOException $e) {
            error_log("Error obtenerEntrenadorPorId: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener un entrenador por DNI
     */
    public function obtenerEntrenadorPorDni($dni, $idExcluir = null) {
        try {
            $sql = "SELECT id FROM entrenadores WHERE dni = :dni";
            if ($idExcluir) {
                $sql .= " AND id != :id_excluir";
            }
            $stmt = $this->conPDO->prepare($sql);
            $stmt->bindParam(':dni', $dni);
            if ($idExcluir) {
                $stmt->bindParam(':id_excluir', $idExcluir);
            }
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerEntrenadorPorDni: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Verificar si un código de barras ya existe
     */
    public function codigoBarcodeExiste($codigo, $excluirId = null) {
        try {
            $sql = "SELECT COUNT(*) FROM entrenadores WHERE codigo_barcode = :codigo";
            
            if ($excluirId) {
                $sql .= " AND id != :excluir_id";
            }
            
            $stmt = $this->conPDO->prepare($sql);
            $stmt->bindParam(':codigo', $codigo);
            
            if ($excluirId) {
                $stmt->bindParam(':excluir_id', $excluirId);
            }
            
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Error codigoBarcodeExiste: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Generar código de barras único
     */
    public function generarCodigoBarcode() {
        do {
            $codigo = 'ENT' . str_pad(rand(100000000, 999999999), 9, '0', STR_PAD_LEFT);
        } while ($this->codigoBarcodeExiste($codigo));
        
        return $codigo;
    }

    /**
     * Crear nuevo entrenador
     */
    public function crearEntrenador($datos) {
        $this->conPDO->beginTransaction();
        try {
            $sql = "INSERT INTO entrenadores (nombre, apellido, dni, direccion, telefono, email, fecha_nacimiento, estado, numero_entrenador) 
                    VALUES (:nombre, :apellido, :dni, :direccion, :telefono, :email, :fecha_nacimiento, :estado, :numero_entrenador)";
            
            $stmt = $this->conPDO->prepare($sql);
            
            $stmt->bindParam(':nombre', $datos['nombre']);
            $stmt->bindParam(':apellido', $datos['apellido']);
            $stmt->bindParam(':dni', $datos['dni']);
            $stmt->bindParam(':direccion', $datos['direccion']);
            $stmt->bindParam(':telefono', $datos['telefono']);
            $stmt->bindParam(':email', $datos['email']);
            $stmt->bindParam(':fecha_nacimiento', $datos['fecha_nacimiento']);
            $stmt->bindParam(':estado', $datos['estado']);
            $stmt->bindParam(':numero_entrenador', $datos['numero_entrenador']);
            
            $stmt->execute();
            $entrenadorId = $this->conPDO->lastInsertId();

            // Asociar tipos de entrenador
            if (!empty($datos['tipos']) && is_array($datos['tipos'])) {
                $this->asociarTipos($entrenadorId, $datos['tipos']);
            }

            $this->conPDO->commit();
            return true;
        } catch (PDOException $e) {
            $this->conPDO->rollBack();
            error_log("Error crearEntrenador: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualizar entrenador
     */
    public function actualizarEntrenador($id, $datos) {
        $this->conPDO->beginTransaction();
        try {
            $sql = "UPDATE entrenadores SET 
                    nombre = :nombre,
                    apellido = :apellido,
                    dni = :dni,
                    direccion = :direccion,
                    telefono = :telefono,
                    email = :email,
                    fecha_nacimiento = :fecha_nacimiento,
                    estado = :estado
                    WHERE id = :id";
            
            $stmt = $this->conPDO->prepare($sql);
            
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':nombre', $datos['nombre']);
            $stmt->bindParam(':apellido', $datos['apellido']);
            $stmt->bindParam(':dni', $datos['dni']);
            $stmt->bindParam(':direccion', $datos['direccion']);
            $stmt->bindParam(':telefono', $datos['telefono']);
            $stmt->bindParam(':email', $datos['email']);
            $stmt->bindParam(':fecha_nacimiento', $datos['fecha_nacimiento']);
            $stmt->bindParam(':estado', $datos['estado']);
            
            $stmt->execute();

            // Actualizar tipos de entrenador
            $this->actualizarTipos($id, $datos['tipos'] ?? []);

            $this->conPDO->commit();
            return true;
        } catch (PDOException $e) {
            $this->conPDO->rollBack();
            error_log("Error actualizarEntrenador: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Eliminar entrenador
     */
    public function eliminarEntrenador($id) {
        try {
            // La FK en entrenador_tipo tiene ON DELETE CASCADE, así que no es necesario borrar manualmente
            $sql = "DELETE FROM entrenadores WHERE id = :id";
            $stmt = $this->conPDO->prepare($sql);
            $stmt->bindParam(':id', $id);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error eliminarEntrenador: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener todos los tipos de entrenador
     */
    public function obtenerTiposEntrenador() {
        try {
            $sql = "SELECT id, nombre FROM tipo_entrenador ORDER BY nombre";
            $stmt = $this->conPDO->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerTiposEntrenador: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener los IDs de los tipos de un entrenador
     */
    public function obtenerTiposDeEntrenador($entrenadorId) {
        try {
            $sql = "SELECT tipo_id FROM entrenador_tipo WHERE entrenador_id = :entrenador_id";
            $stmt = $this->conPDO->prepare($sql);
            $stmt->bindParam(':entrenador_id', $entrenadorId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            error_log("Error obtenerTiposDeEntrenador: " . $e->getMessage());
            return [];
        }
    }

    private function asociarTipos($entrenadorId, $tipos) {
        $sql = "INSERT INTO entrenador_tipo (entrenador_id, tipo_id) VALUES (:entrenador_id, :tipo_id)";
        $stmt = $this->conPDO->prepare($sql);
        
        foreach ($tipos as $tipoId) {
            $stmt->bindParam(':entrenador_id', $entrenadorId);
            $stmt->bindParam(':tipo_id', $tipoId);
            $stmt->execute();
        }
    }

    private function actualizarTipos($entrenadorId, $tipos) {
        // 1. Eliminar las asociaciones existentes
        $sqlDelete = "DELETE FROM entrenador_tipo WHERE entrenador_id = :entrenador_id";
        $stmtDelete = $this->conPDO->prepare($sqlDelete);
        $stmtDelete->bindParam(':entrenador_id', $entrenadorId);
        $stmtDelete->execute();

        // 2. Insertar las nuevas asociaciones
        if (!empty($tipos) && is_array($tipos)) {
            $this->asociarTipos($entrenadorId, $tipos);
        }
    }
}

?>
