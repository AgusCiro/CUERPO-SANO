<?php
include_once 'app/models/conexion.php';

try {
    $con = conectar();
    $filtro = '12345678';
    
    echo "=== DEBUG DE BÚSQUEDA ===\n";
    echo "Filtro: " . $filtro . "\n\n";
    
    // Consulta exacta que está usando el modelo
    $sql = "SELECT c.*, m.dni as dni_usuario, m.email as email_usuario 
            FROM clientes c 
            LEFT JOIN miembros m ON c.usuario_id = m.id
            WHERE c.nombre LIKE :filtro 
            OR c.apellido LIKE :filtro 
            OR c.numero_cliente LIKE :filtro
            OR c.dni LIKE :filtro
            ORDER BY c.apellido, c.nombre";
    
    echo "SQL: " . $sql . "\n\n";
    
    $stmt = $con->prepare($sql);
    $filtroParam = "%{$filtro}%";
    $stmt->bindParam(':filtro', $filtroParam);
    $stmt->execute();
    
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Número de resultados: " . count($resultados) . "\n\n";
    
    if (count($resultados) > 0) {
        echo "Resultados encontrados:\n";
        foreach($resultados as $cliente) {
            echo "ID: {$cliente['id']}, Nombre: {$cliente['nombre']}, Apellido: {$cliente['apellido']}, DNI: {$cliente['dni']}, Número Cliente: {$cliente['numero_cliente']}\n";
        }
    } else {
        echo "No se encontraron resultados.\n\n";
        
        // Vamos a probar cada condición por separado
        echo "=== PROBANDO CADA CONDICIÓN POR SEPARADO ===\n";
        
        // 1. Buscar por nombre
        $stmt = $con->prepare("SELECT * FROM clientes WHERE nombre LIKE ?");
        $stmt->execute(["%{$filtro}%"]);
        $porNombre = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "Por nombre: " . count($porNombre) . " resultados\n";
        
        // 2. Buscar por apellido
        $stmt = $con->prepare("SELECT * FROM clientes WHERE apellido LIKE ?");
        $stmt->execute(["%{$filtro}%"]);
        $porApellido = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "Por apellido: " . count($porApellido) . " resultados\n";
        
        // 3. Buscar por numero_cliente
        $stmt = $con->prepare("SELECT * FROM clientes WHERE numero_cliente LIKE ?");
        $stmt->execute(["%{$filtro}%"]);
        $porNumero = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "Por numero_cliente: " . count($porNumero) . " resultados\n";
        
        // 4. Buscar por dni (exacto)
        $stmt = $con->prepare("SELECT * FROM clientes WHERE dni = ?");
        $stmt->execute([$filtro]);
        $porDniExacto = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "Por DNI exacto: " . count($porDniExacto) . " resultados\n";
        
        // 5. Buscar por dni (con LIKE)
        $stmt = $con->prepare("SELECT * FROM clientes WHERE dni LIKE ?");
        $stmt->execute(["%{$filtro}%"]);
        $porDniLike = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "Por DNI con LIKE: " . count($porDniLike) . " resultados\n";
        
        if (count($porDniExacto) > 0) {
            echo "\nCliente encontrado por DNI exacto:\n";
            foreach($porDniExacto as $cliente) {
                echo "ID: {$cliente['id']}, Nombre: {$cliente['nombre']}, Apellido: {$cliente['apellido']}, DNI: {$cliente['dni']}\n";
            }
        }
    }
    
} catch(Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
?>
