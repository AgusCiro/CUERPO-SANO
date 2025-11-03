<?php

include_once __DIR__ . '/conexion.php';

class Clase {
    private $conPDO;

    public function __construct() {
        $this->conPDO = conectar();
        if ($this->conPDO instanceof PDO) {
            $this->conPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conPDO->exec("SET NAMES 'utf8'");
        }
    }

    /**
     * Obtener todas las clases con el nombre de la actividad y el entrenador
     */
    public function obtenerClases() {
        try {
            $sql = "SELECT c.*, a.nombre as nombre_actividad, e.nombre as nombre_entrenador, e.apellido as apellido_entrenador
                    FROM clases c
                    JOIN actividades a ON c.actividad_id = a.id
                    LEFT JOIN entrenadores e ON c.entrenador_id = e.id
                    ORDER BY c.nombre";
            $stmt = $this->conPDO->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerClases: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener una clase por su ID
     */
    public function obtenerClasePorId($id) {
        try {
            $sql = "SELECT c.*, a.nombre as nombre_actividad, e.nombre as nombre_entrenador, e.apellido as apellido_entrenador
                    FROM clases c
                    JOIN actividades a ON c.actividad_id = a.id
                    LEFT JOIN entrenadores e ON c.entrenador_id = e.id
                    WHERE c.id = :id";
            $stmt = $this->conPDO->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerClasePorId: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Crear una nueva clase
     */
    public function crearClase($datos) {
        try {
            $sql = "INSERT INTO clases (actividad_id, entrenador_id, nombre, capacidad, activo) 
                    VALUES (:actividad_id, :entrenador_id, :nombre, :capacidad, 1)";
            $stmt = $this->conPDO->prepare($sql);
            $stmt->bindParam(':actividad_id', $datos['actividad_id'], PDO::PARAM_INT);
            $stmt->bindParam(':entrenador_id', $datos['entrenador_id'], PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $datos['nombre']);
            $stmt->bindParam(':capacidad', $datos['capacidad'], PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en crearClase: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualizar una clase existente
     */
    public function actualizarClase($id, $datos) {
        try {
            $sql = "UPDATE clases SET 
                    actividad_id = :actividad_id, 
                    entrenador_id = :entrenador_id, 
                    nombre = :nombre, 
                    capacidad = :capacidad
                    WHERE id = :id";
            $stmt = $this->conPDO->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':actividad_id', $datos['actividad_id'], PDO::PARAM_INT);
            $stmt->bindParam(':entrenador_id', $datos['entrenador_id'], PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $datos['nombre']);
            $stmt->bindParam(':capacidad', $datos['capacidad'], PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en actualizarClase: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Eliminar una clase (Baja lógica)
     */
    public function eliminarClase($id) {
        try {
            $sql = "DELETE FROM clases WHERE id = :id";
            $stmt = $this->conPDO->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en eliminarClase: " . $e->getMessage());
            return false;
        }
    }
}

?>