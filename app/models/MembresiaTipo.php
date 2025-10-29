<?php

include_once __DIR__ . '/conexion.php';

class MembresiaTipo {
    private $conPDO;

    public function __construct() {
        $this->conPDO = conectar();
        if ($this->conPDO instanceof PDO) {
            $this->conPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conPDO->exec("SET NAMES 'utf8'");
        }
    }

    /**
     * Obtener todos los tipos de membresía
     */
    public function obtenerMembresiaTipos() {
        try {
            $sql = "SELECT * FROM membresia_tipos WHERE activo = 1 ORDER BY nombre";
            $stmt = $this->conPDO->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerMembresiaTipos: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener un tipo de membresía por ID
     */
    public function obtenerMembresiaTipoPorId($id) {
        try {
            $sql = "SELECT * FROM membresia_tipos WHERE id = :id";
            $stmt = $this->conPDO->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerMembresiaTipoPorId: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Crear nuevo tipo de membresía
     */
    public function crearMembresiaTipo($datos) {
        try {
            $sql = "INSERT INTO membresia_tipos (nombre, duracion, precio, descripcion, activo) 
                    VALUES (:nombre, :duracion, :precio, :descripcion, 1)";
            
            $stmt = $this->conPDO->prepare($sql);
            
            $stmt->bindParam(':nombre', $datos['nombre']);
            $stmt->bindParam(':duracion', $datos['duracion']);
            $stmt->bindParam(':precio', $datos['precio']);
            $stmt->bindParam(':descripcion', $datos['descripcion']);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error crearMembresiaTipo: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualizar tipo de membresía
     */
    public function actualizarMembresiaTipo($id, $datos) {
        try {
            $sql = "UPDATE membresia_tipos SET 
                    nombre = :nombre,
                    duracion = :duracion,
                    precio = :precio,
                    descripcion = :descripcion,
                    activo = :activo
                    WHERE id = :id";
            
            $stmt = $this->conPDO->prepare($sql);
            
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':nombre', $datos['nombre']);
            $stmt->bindParam(':duracion', $datos['duracion']);
            $stmt->bindParam(':precio', $datos['precio']);
            $stmt->bindParam(':descripcion', $datos['descripcion']);
            $stmt->bindParam(':activo', $datos['activo']);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error actualizarMembresiaTipo: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Eliminar tipo de membresía (marcar como inactivo)
     */
    public function eliminarMembresiaTipo($id) {
        try {
            $sql = "UPDATE membresia_tipos SET activo = 0 WHERE id = :id";
            $stmt = $this->conPDO->prepare($sql);
            $stmt->bindParam(':id', $id);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error eliminarMembresiaTipo: " . $e->getMessage());
            return false;
        }
    }
}

?>