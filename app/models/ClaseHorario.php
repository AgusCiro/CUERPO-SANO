<?php

include_once __DIR__ . '/conexion.php';

class ClaseHorario {
    private $conPDO;

    public function __construct() {
        $this->conPDO = conectar();
        if ($this->conPDO instanceof PDO) {
            $this->conPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conPDO->exec("SET NAMES 'utf8'");
        }
    }

    /**
     * Obtener todos los horarios para una clase específica
     */
    public function obtenerHorariosPorClaseId($clase_id) {
        try {
            $sql = "SELECT * FROM clase_horarios WHERE clase_id = :clase_id ORDER BY fecha_inicio";
            $stmt = $this->conPDO->prepare($sql);
            $stmt->bindParam(':clase_id', $clase_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerHorariosPorClaseId: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener un horario por su ID
     */
    public function obtenerHorarioPorId($id) {
        try {
            $sql = "SELECT * FROM clase_horarios WHERE id = :id";
            $stmt = $this->conPDO->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerHorarioPorId: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Crear un nuevo horario
     */
    public function crearHorario($datos) {
        try {
            // Al crear, el cupo restante es igual al cupo total
            $sql = "INSERT INTO clase_horarios (clase_id, fecha_inicio, fecha_fin, ubicacion, cupo, cupo_restante) 
                    VALUES (:clase_id, :fecha_inicio, :fecha_fin, :ubicacion, :cupo, :cupo)";
            $stmt = $this->conPDO->prepare($sql);
            $stmt->bindParam(':clase_id', $datos['clase_id'], PDO::PARAM_INT);
            $stmt->bindParam(':fecha_inicio', $datos['fecha_inicio']);
            $stmt->bindParam(':fecha_fin', $datos['fecha_fin']);
            $stmt->bindParam(':ubicacion', $datos['ubicacion']);
            $stmt->bindParam(':cupo', $datos['cupo'], PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en crearHorario: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualizar un horario existente
     */
    public function actualizarHorario($id, $datos, $inscritos) {
        try {
            $cupo_restante = $datos['cupo'] - $inscritos;

            $sql = "UPDATE clase_horarios SET 
                    fecha_inicio = :fecha_inicio, 
                    fecha_fin = :fecha_fin, 
                    ubicacion = :ubicacion, 
                    cupo = :cupo,
                    cupo_restante = :cupo_restante
                    WHERE id = :id";
            $stmt = $this->conPDO->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':fecha_inicio', $datos['fecha_inicio']);
            $stmt->bindParam(':fecha_fin', $datos['fecha_fin']);
            $stmt->bindParam(':ubicacion', $datos['ubicacion']);
            $stmt->bindParam(':cupo', $datos['cupo'], PDO::PARAM_INT);
            $stmt->bindParam(':cupo_restante', $cupo_restante, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en actualizarHorario: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Eliminar un horario
     */
    public function eliminarHorario($id) {
        try {
            $sql = "DELETE FROM clase_horarios WHERE id = :id";
            $stmt = $this->conPDO->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en eliminarHorario: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Verifica si existe superposición de horarios para una misma clase.
     */
    public function verificarSuperposicion($clase_id, $fecha_inicio, $fecha_fin, $horario_id_excluir = null) {
        try {
            $sql = "SELECT COUNT(*) FROM clase_horarios 
                    WHERE clase_id = :clase_id 
                    AND (
                        (:fecha_inicio < fecha_fin AND :fecha_fin > fecha_inicio)
                    )";
            if ($horario_id_excluir) {
                $sql .= " AND id != :horario_id_excluir";
            }
            $stmt = $this->conPDO->prepare($sql);
            $stmt->bindParam(':clase_id', $clase_id, PDO::PARAM_INT);
            $stmt->bindParam(':fecha_inicio', $fecha_inicio);
            $stmt->bindParam(':fecha_fin', $fecha_fin);
            if ($horario_id_excluir) {
                $stmt->bindParam(':horario_id_excluir', $horario_id_excluir, PDO::PARAM_INT);
            }
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Error en verificarSuperposicion: " . $e->getMessage());
            return true; // Prevenir inserción en caso de error
        }
    }

    /**
     * Restablece el cupo de un horario y elimina todas las inscripciones asociadas.
     */
    public function restablecerHorario($horario_id) {
        $this->conPDO->beginTransaction();
        try {
            // 1. Eliminar todas las inscripciones para este horario
            $sqlDelete = "DELETE FROM inscripciones_clase WHERE clase_horario_id = :horario_id";
            $stmtDelete = $this->conPDO->prepare($sqlDelete);
            $stmtDelete->bindParam(':horario_id', $horario_id, PDO::PARAM_INT);
            $stmtDelete->execute();

            // 2. Restablecer el cupo restante del horario
            $sqlUpdate = "UPDATE clase_horarios SET cupo_restante = cupo WHERE id = :horario_id";
            $stmtUpdate = $this->conPDO->prepare($sqlUpdate);
            $stmtUpdate->bindParam(':horario_id', $horario_id, PDO::PARAM_INT);
            $stmtUpdate->execute();

            $this->conPDO->commit();
            return true;
        } catch (PDOException $e) {
            $this->conPDO->rollBack();
            error_log("Error en restablecerHorario: " . $e->getMessage());
            return false;
        }
    }
}

?>