<?php
header('Content-Type: application/json');

$dns = 'odbc:gebipadesa';
$user = 'gebipa';
$pwd = 'gebipa';

try {
    $conn = new PDO($dns, $user, $pwd, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}

// Obtener acción del parámetro
$action = $_GET['action'] ?? '';

if ($action === 'listar') {
    // Obtener registros de la base de datos
    $stmt = $conn->query("SELECT id_celda, destino, nro_celda, capacidad, superficie, femenino FROM tbl_ac_celdas");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

if ($action === 'save') {
    $id_celda = $_POST['celdaid'] ?? '';
    $destino = $_POST['destino'] ?? '';
    $nro_celda = $_POST['celda'] ?? '';
    $capacidad = $_POST['capacidad'] ?? '';
    $superficie = $_POST['superficie'] ?? '';
    $femenino = $_POST['femenino'] ?? '';
    //echo $id_celda;
    if ($id_celda !== '') {
        $query = "UPDATE tbl_ac_celdas SET DESTINO=?, NRO_CELDA=?, CAPACIDAD=?, SUPERFICIE=?,FEMENINO=? WHERE ID_CELDA=?";
        $sentencia = $conn->prepare($query);
        $sentencia->bindParam(1, $destino, PDO::PARAM_STR);
        $sentencia->bindParam(2, $nro_celda, PDO::PARAM_INT);
        $sentencia->bindParam(3, $capacidad, PDO::PARAM_INT);
        $sentencia->bindParam(4, $superficie, PDO::PARAM_INT);
        $sentencia->bindParam(5, $femenino, PDO::PARAM_STR);
        $sentencia->bindParam(6, $id_celda, PDO::PARAM_INT);
        $success = $sentencia->execute();
    } else {
        $query = "INSERT INTO tbl_ac_celdas(DESTINO, NRO_CELDA, CAPACIDAD, SUPERFICIE, FEMENINO) VALUES(?,?,?,?,?)";
        $sentencia = $conn->prepare($query);
        $sentencia->bindParam(1, $destino, PDO::PARAM_STR);
        $sentencia->bindParam(2, $nro_celda, PDO::PARAM_INT);
        $sentencia->bindParam(3, $capacidad, PDO::PARAM_INT);
        $sentencia->bindParam(4, $superficie, PDO::PARAM_INT);
        $sentencia->bindParam(5, $femenino, PDO::PARAM_STR);
        $success = $sentencia->execute();
    }
    
    echo json_encode(['success' => $success]);
}
    
    $id_celda = $_POST['celdaid'] ?? $_REQUEST['celdaid'] ?? '';

    // Asegurarse de que ID_CELDA tiene un valor antes de proceder
    if ($id_celda) {
        $query = "DELETE FROM tbl_ac_celdas WHERE ID_CELDA= ?";
        $sentencia = $conn->prepare($query);
        $sentencia->bindParam(1, $id_celda, PDO::PARAM_INT);
        $success = $sentencia->execute();
        
        // Enviar respuesta JSON válida
        echo json_encode(['success' => $success]);
    } else {
        echo json_encode(['success' => false, 'error' => 'ID_CELDA no proporcionado']);
    }
}
function selectDestinos(){

    $query = "SELECT DISTINCT DESTINO
    FROM TBL_AC_CELDAS"

}
