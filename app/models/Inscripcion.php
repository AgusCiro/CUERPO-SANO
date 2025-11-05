<?php

include_once __DIR__ . '/conexion.php';

class Inscripcion {
    private $conPDO;

    public function __construct() {
        $this->conPDO = conectar();
        if ($this->conPDO instanceof PDO) {
            $this->conPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conPDO->exec("SET NAMES 'utf8'");
        }
    }

    /**
     * Obtiene los clientes inscritos en un horario específico.
     */
    public function obtenerInscripcionesPorHorario($clase_horario_id) {
        try {
            $sql = "SELECT i.id, i.fecha_inscripcion, c.nombre, c.apellido, c.dni
                    FROM inscripciones_clase i
                    JOIN clientes c ON i.cliente_id = c.id
                    WHERE i.clase_horario_id = :clase_horario_id
                    ORDER BY c.apellido, c.nombre";
            $stmt = $this->conPDO->prepare($sql);
            $stmt->bindParam(':clase_horario_id', $clase_horario_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerInscripcionesPorHorario: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Inscribe un cliente en un horario de clase, manejando la transacción.
     */
    public function inscribirCliente($clase_horario_id, $cliente_id) {
        $this->conPDO->beginTransaction();
        try {
            // 1. Obtener el horario y bloquear la fila para evitar race conditions
            $sqlHorario = "SELECT * FROM clase_horarios WHERE id = :clase_horario_id FOR UPDATE";
            $stmtHorario = $this->conPDO->prepare($sqlHorario);
            $stmtHorario->bindParam(':clase_horario_id', $clase_horario_id, PDO::PARAM_INT);
            $stmtHorario->execute();
            $horario = $stmtHorario->fetch(PDO::FETCH_ASSOC);

            if (!$horario) {
                throw new Exception("El horario no existe.");
            }

            // 2. Verificar si hay cupo disponible
            if ($horario['cupo_restante'] <= 0) {
                throw new Exception("No hay cupo disponible para esta clase.");
            }

            // 3. Verificar si el cliente ya tiene un conflicto de horario
            if ($this->clienteTieneConflictoHorario($cliente_id, $horario['fecha_inicio'], $horario['fecha_fin'])) {
                throw new Exception("El cliente ya está inscrito en otra clase en un horario que se superpone.");
            }

            // 4. Insertar la nueva inscripción
            $sqlInsert = "INSERT INTO inscripciones_clase (clase_horario_id, cliente_id, fecha_inscripcion, estado) VALUES (:clase_horario_id, :cliente_id, NOW(), 'inscripto')";
            $stmtInsert = $this->conPDO->prepare($sqlInsert);
            $stmtInsert->bindParam(':clase_horario_id', $clase_horario_id, PDO::PARAM_INT);
            $stmtInsert->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
            $stmtInsert->execute();

            // 5. Actualizar el cupo restante
            $sqlUpdate = "UPDATE clase_horarios SET cupo_restante = cupo_restante - 1 WHERE id = :clase_horario_id";
            $stmtUpdate = $this->conPDO->prepare($sqlUpdate);
            $stmtUpdate->bindParam(':clase_horario_id', $clase_horario_id, PDO::PARAM_INT);
            $stmtUpdate->execute();

            $this->conPDO->commit();
            return true;

        } catch (Exception $e) {
            $this->conPDO->rollBack();
            error_log("Error en inscribirCliente: " . $e->getMessage());
            // Devolver el mensaje de error específico para mostrar al usuario
            return $e->getMessage();
        }
    }

    /**
     * Cancela la inscripción de un cliente.
     */
    public function cancelarInscripcion($inscripcion_id) {
        $this->conPDO->beginTransaction();
        try {
            // 1. Obtener datos de la inscripción
            $sqlInscripcion = "SELECT clase_horario_id FROM inscripciones_clase WHERE id = :inscripcion_id";
            $stmtInscripcion = $this->conPDO->prepare($sqlInscripcion);
            $stmtInscripcion->bindParam(':inscripcion_id', $inscripcion_id, PDO::PARAM_INT);
            $stmtInscripcion->execute();
            $inscripcion = $stmtInscripcion->fetch(PDO::FETCH_ASSOC);

            if (!$inscripcion) {
                throw new Exception("La inscripción no existe.");
            }

            // 2. Eliminar la inscripción
            $sqlDelete = "DELETE FROM inscripciones_clase WHERE id = :inscripcion_id";
            $stmtDelete = $this->conPDO->prepare($sqlDelete);
            $stmtDelete->bindParam(':inscripcion_id', $inscripcion_id, PDO::PARAM_INT);
            $stmtDelete->execute();

            // 3. Incrementar el cupo restante
            $sqlUpdate = "UPDATE clase_horarios SET cupo_restante = cupo_restante + 1 WHERE id = :clase_horario_id";
            $stmtUpdate = $this->conPDO->prepare($sqlUpdate);
            $stmtUpdate->bindParam(':clase_horario_id', $inscripcion['clase_horario_id'], PDO::PARAM_INT);
            $stmtUpdate->execute();

            $this->conPDO->commit();
            return true;

        } catch (Exception $e) {
            $this->conPDO->rollBack();
            error_log("Error en cancelarInscripcion: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Verifica si un cliente ya tiene una clase en un horario que se superpone.
     */
    private function clienteTieneConflictoHorario($cliente_id, $fecha_inicio_nueva, $fecha_fin_nueva) {
        $sql = "SELECT COUNT(*) 
                FROM inscripciones_clase i
                JOIN clase_horarios h ON i.clase_horario_id = h.id
                WHERE i.cliente_id = :cliente_id
                AND (:fecha_inicio_nueva < h.fecha_fin AND :fecha_fin_nueva > h.fecha_inicio)";
        
        $stmt = $this->conPDO->prepare($sql);
        $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
        $stmt->bindParam(':fecha_inicio_nueva', $fecha_inicio_nueva);
        $stmt->bindParam(':fecha_fin_nueva', $fecha_fin_nueva);
        $stmt->execute();
        
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Elimina todas las inscripciones de un horario específico.
     */
    public function eliminarInscripcionesPorHorario($clase_horario_id) {
        try {
            $sql = "DELETE FROM inscripciones_clase WHERE clase_horario_id = :clase_horario_id";
            $stmt = $this->conPDO->prepare($sql);
            $stmt->bindParam(':clase_horario_id', $clase_horario_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en eliminarInscripcionesPorHorario: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cuenta el número de clientes inscritos en un horario específico.
     */
    public function contarInscripcionesPorHorario($clase_horario_id) {
        try {
            $sql = "SELECT COUNT(*) FROM inscripciones_clase WHERE clase_horario_id = :clase_horario_id";
            $stmt = $this->conPDO->prepare($sql);
            $stmt->bindParam(':clase_horario_id', $clase_horario_id, PDO::PARAM_INT);
            $stmt->execute();
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error en contarInscripcionesPorHorario: " . $e->getMessage());
            return 0;
        }
    }
}

?>