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
                         OR e.email LIKE :filtro";
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
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerEntrenadorPorId: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Crear nuevo entrenador
     */
    public function crearEntrenador($datos) {
        try {
            $sql = "INSERT INTO entrenadores (nombre, apellido, dni, telefono, email) 
                    VALUES (:nombre, :apellido, :dni, :telefono, :email)";
            
            $stmt = $this->conPDO->prepare($sql);
            
            $stmt->bindParam(':nombre', $datos['nombre']);
            $stmt->bindParam(':apellido', $datos['apellido']);
            $stmt->bindParam(':dni', $datos['dni']);
            $stmt->bindParam(':telefono', $datos['telefono']);
            $stmt->bindParam(':email', $datos['email']);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error crearEntrenador: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualizar entrenador
     */
    public function actualizarEntrenador($id, $datos) {
        try {
            $sql = "UPDATE entrenadores SET 
                    nombre = :nombre,
                    apellido = :apellido,
                    dni = :dni,
                    telefono = :telefono,
                    email = :email
                    WHERE id = :id";
            
            $stmt = $this->conPDO->prepare($sql);
            
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':nombre', $datos['nombre']);
            $stmt->bindParam(':apellido', $datos['apellido']);
            $stmt->bindParam(':dni', $datos['dni']);
            $stmt->bindParam(':telefono', $datos['telefono']);
            $stmt->bindParam(':email', $datos['email']);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error actualizarEntrenador: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Eliminar entrenador
     */
    public function eliminarEntrenador($id) {
        try {
            $sql = "DELETE FROM entrenadores WHERE id = :id";
            $stmt = $this->conPDO->prepare($sql);
            $stmt->bindParam(':id', $id);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error eliminarEntrenador: " . $e->getMessage());
            return false;
        }
    }
}

?>
