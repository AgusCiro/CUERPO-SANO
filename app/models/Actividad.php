<?php

include_once __DIR__ . '/conexion.php';

class Actividad {
    private $conPDO;

    public function __construct() {
        $this->conPDO = conectar();
        if ($this->conPDO instanceof PDO) {
            $this->conPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conPDO->exec("SET NAMES 'utf8'");
        }
    }

    /**
     * Obtener todas las actividades
     */
    public function obtenerActividades() {
        try {
            $sql = "SELECT * FROM actividades ORDER BY nombre";
            $stmt = $this->conPDO->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerActividades: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener una actividad por su ID
     */
    public function obtenerActividadPorId($id) {
        try {
            $sql = "SELECT * FROM actividades WHERE id = :id";
            $stmt = $this->conPDO->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerActividadPorId: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Crear una nueva actividad
     */
    public function crearActividad($datos) {
        try {
            $sql = "INSERT INTO actividades (nombre, descripcion, duracion_minutos, intensidad, activo) VALUES (:nombre, :descripcion, :duracion_minutos, :intensidad, 1)";
            $stmt = $this->conPDO->prepare($sql);
            $stmt->bindParam(':nombre', $datos['nombre']);
            $stmt->bindParam(':descripcion', $datos['descripcion']);
            $stmt->bindParam(':duracion_minutos', $datos['duracion_minutos'], PDO::PARAM_INT);
            $stmt->bindParam(':intensidad', $datos['intensidad']);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en crearActividad: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualizar una actividad existente
     */
    public function actualizarActividad($id, $datos) {
        try {
            $sql = "UPDATE actividades SET nombre = :nombre, descripcion = :descripcion, duracion_minutos = :duracion_minutos, intensidad = :intensidad WHERE id = :id";
            $stmt = $this->conPDO->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $datos['nombre']);
            $stmt->bindParam(':descripcion', $datos['descripcion']);
            $stmt->bindParam(':duracion_minutos', $datos['duracion_minutos'], PDO::PARAM_INT);
            $stmt->bindParam(':intensidad', $datos['intensidad']);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en actualizarActividad: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Eliminar una actividad (Baja física)
     */
    public function eliminarActividad($id) {
        try {
            $sql = "DELETE FROM actividades WHERE id = :id";
            $stmt = $this->conPDO->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en eliminarActividad: " . $e->getMessage());
            return false;
        }
    }
}

?>