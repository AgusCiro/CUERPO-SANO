<?php

include_once __DIR__ . '/conexion.php';

class Membresia {
    private $conPDO;

    public function __construct() {
        $this->conPDO = conectar();
        if ($this->conPDO instanceof PDO) {
            $this->conPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conPDO->exec("SET NAMES 'utf8'");
        }
    }

    /**
     * Obtener todas las membresías
     */
    public function obtenerMembresias($filtro = '') {
        try {
            $sql = "SELECT m.*, c.nombre as cliente_nombre, c.apellido as cliente_apellido, mt.nombre as tipo_nombre
                    FROM membresias m
                    JOIN clientes c ON m.cliente_id = c.id
                    JOIN membresia_tipos mt ON m.tipo_id = mt.id";
            if (!empty($filtro)) {
                $sql .= " WHERE c.nombre LIKE :filtro 
                         OR c.apellido LIKE :filtro
                         OR mt.nombre LIKE :filtro";
            }
            
            $sql .= " ORDER BY m.fecha_inicio DESC";
            
            $stmt = $this->conPDO->prepare($sql);
            
            if (!empty($filtro)) {
                $filtroParam = "%{$filtro}%";
                $stmt->bindParam(':filtro', $filtroParam);
            }
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerMembresias: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener una membresía por ID
     */
    public function obtenerMembresiaPorId($id) {
        try {
            $sql = "SELECT m.* 
                    FROM membresias m
                    WHERE m.id = :id";
            
            $stmt = $this->conPDO->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerMembresiaPorId: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Crear nueva membresía
     */
    public function crearMembresia($datos) {
        try {
            $sql = "INSERT INTO membresias (cliente_id, tipo_id, fecha_inicio, fecha_fin, precio_pagado, estado, numero_comprobante) 
                    VALUES (:cliente_id, :tipo_id, :fecha_inicio, :fecha_fin, :precio_pagado, :estado, :numero_comprobante)";
            
            $stmt = $this->conPDO->prepare($sql);
            
            $stmt->bindParam(':cliente_id', $datos['cliente_id']);
            $stmt->bindParam(':tipo_id', $datos['tipo_id']);
            $stmt->bindParam(':fecha_inicio', $datos['fecha_inicio']);
            $stmt->bindParam(':fecha_fin', $datos['fecha_fin']);
            $stmt->bindParam(':precio_pagado', $datos['precio_pagado']);
            $stmt->bindParam(':estado', $datos['estado']);
            $stmt->bindParam(':numero_comprobante', $datos['numero_comprobante']);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error crearMembresia: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualizar membresía
     */
    public function actualizarMembresia($id, $datos) {
        try {
            $sql = "UPDATE membresias SET 
                    cliente_id = :cliente_id,
                    tipo_id = :tipo_id,
                    fecha_inicio = :fecha_inicio,
                    fecha_fin = :fecha_fin,
                    precio_pagado = :precio_pagado,
                    estado = :estado,
                    numero_comprobante = :numero_comprobante
                    WHERE id = :id";
            
            $stmt = $this->conPDO->prepare($sql);
            
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':cliente_id', $datos['cliente_id']);
            $stmt->bindParam(':tipo_id', $datos['tipo_id']);
            $stmt->bindParam(':fecha_inicio', $datos['fecha_inicio']);
            $stmt->bindParam(':fecha_fin', $datos['fecha_fin']);
            $stmt->bindParam(':precio_pagado', $datos['precio_pagado']);
            $stmt->bindParam(':estado', $datos['estado']);
            $stmt->bindParam(':numero_comprobante', $datos['numero_comprobante']);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error actualizarMembresia: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Eliminar membresía
     */
    public function eliminarMembresia($id) {
        try {
            $sql = "DELETE FROM membresias WHERE id = :id";
            $stmt = $this->conPDO->prepare($sql);
            $stmt->bindParam(':id', $id);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error eliminarMembresia: " . $e->getMessage());
            return false;
        }
    }
}

?>