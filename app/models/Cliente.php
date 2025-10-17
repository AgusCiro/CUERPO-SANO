<?php

include_once __DIR__ . '/conexion.php';

class Cliente {
    private $conPDO;

    public function __construct() {
        $this->conPDO = conectar();
        if ($this->conPDO instanceof PDO) {
            $this->conPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conPDO->exec("SET NAMES 'utf8'");
        }
    }

    /**
     * Obtener todos los clientes
     */
    public function obtenerClientes($filtro = '') {
        try {
            $sql = "SELECT c.* 
                    FROM clientes c";
            if (!empty($filtro)) {
                $sql .= " WHERE c.nombre LIKE :filtro 
                         OR c.apellido LIKE :filtro 
                         OR c.numero_cliente LIKE :filtro
                         OR c.dni LIKE :filtro";
            }
            
            $sql .= " ORDER BY c.apellido, c.nombre";
            
            $stmt = $this->conPDO->prepare($sql);
            
            if (!empty($filtro)) {
                $filtroParam = "%{$filtro}%";
                $stmt->bindParam(':filtro', $filtroParam);
            }
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerClientes: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener un cliente por ID
     */
    public function obtenerClientePorId($id) {
        try {
            $sql = "SELECT c.* 
                    FROM clientes c 
                    WHERE c.id = :id";
            
            $stmt = $this->conPDO->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerClientePorId: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Crear nuevo cliente
     */
    public function crearCliente($datos) {
        try {
            $sql = "INSERT INTO clientes (codigo_barcode, nombre, apellido, dni, direccion, telefono, email, fecha_nacimiento, tipo_descuento, estado) 
                    VALUES (:codigo_barcode, :nombre, :apellido, :dni, :direccion, :telefono, :email, :fecha_nacimiento, :tipo_descuento, :estado)";
            var_dump($datos);
            $stmt = $this->conPDO->prepare($sql);
            
            $stmt->bindParam(':codigo_barcode', $datos['codigo_barcode']);
            $stmt->bindParam(':nombre', $datos['nombre']);
            $stmt->bindParam(':apellido', $datos['apellido']);
            $stmt->bindParam(':dni', $datos['dni']);
            $stmt->bindParam(':direccion', $datos['direccion']);
            $stmt->bindParam(':telefono', $datos['telefono']);
            $stmt->bindParam(':email', $datos['email']);
            $stmt->bindParam(':fecha_nacimiento', $datos['fecha_nacimiento']);
            $stmt->bindParam(':tipo_descuento', $datos['tipo_descuento']);
            $stmt->bindParam(':estado', $datos['estado']);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error crearCliente: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualizar cliente
     */
    public function actualizarCliente($id, $datos) {
        try {
            $sql = "UPDATE clientes SET 
                    nombre = :nombre,
                    apellido = :apellido,
                    direccion = :direccion,
                    telefono = :telefono,
                    email = :email,
                    fecha_nacimiento = :fecha_nacimiento,
                    tipo_descuento = :tipo_descuento,
                    estado = :estado
                    WHERE id = :id";
            
            $stmt = $this->conPDO->prepare($sql);
            
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':nombre', $datos['nombre']);
            $stmt->bindParam(':apellido', $datos['apellido']);
            $stmt->bindParam(':direccion', $datos['direccion']);
            $stmt->bindParam(':telefono', $datos['telefono']);
            $stmt->bindParam(':email', $datos['email']);
            $stmt->bindParam(':fecha_nacimiento', $datos['fecha_nacimiento']);
            $stmt->bindParam(':tipo_descuento', $datos['tipo_descuento']);
            $stmt->bindParam(':estado', $datos['estado']);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error actualizarCliente: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Eliminar cliente
     */
    public function eliminarCliente($id) {
        try {
            $sql = "DELETE FROM clientes WHERE id = :id";
            $stmt = $this->conPDO->prepare($sql);
            $stmt->bindParam(':id', $id);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error eliminarCliente: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Verificar si un código de barras ya existe
     */
    public function codigoBarcodeExiste($codigo, $excluirId = null) {
        try {
            $sql = "SELECT COUNT(*) FROM clientes WHERE codigo_barcode = :codigo";
            
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
            $codigo = 'BAR' . str_pad(rand(100000000, 999999999), 9, '0', STR_PAD_LEFT);
        } while ($this->codigoBarcodeExiste($codigo));
        
        return $codigo;
    }

    /**
     * Obtener estadísticas de clientes
     */
    public function obtenerEstadisticas() {
        try {
            $sql = "SELECT 
                        COUNT(*) as total_clientes,
                        SUM(CASE WHEN estado = 'activo' THEN 1 ELSE 0 END) as clientes_activos,
                        SUM(CASE WHEN estado = 'suspendido' THEN 1 ELSE 0 END) as clientes_suspendidos,
                        SUM(CASE WHEN estado = 'baja' THEN 1 ELSE 0 END) as clientes_baja,
                        SUM(CASE WHEN tipo_descuento = 'estudiante' THEN 1 ELSE 0 END) as estudiantes,
                        SUM(CASE WHEN tipo_descuento = 'mayor' THEN 1 ELSE 0 END) as mayores
                    FROM clientes";
            
            $stmt = $this->conPDO->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerEstadisticas: " . $e->getMessage());
            return [];
        }
    }
}

?>
