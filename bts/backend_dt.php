<?php
header('Content-Type: application/json');

// Conexión a la base de datos con PDO (Oracle o MySQL)
/*
$dsn = 'mysql:host=localhost;dbname=db_crud;charset=utf8'; // Cambia según tu base de datos
$user = 'usuario_db'; // Usuario
$password = 'usuario_db'; // Contraseña

try {
    $conn = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}
*/
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


// Determinar la acción
$action = $_GET['action'] ?? '';

if ($action === 'listar') {
    // Parámetros recibidos de DataTables
    $draw = (int)$_GET['draw'] ?? 1;
    $start = (int)$_GET['start'] ?? 0;
    $length = (int)$_GET['length'] ?? 10;
    $searchValue = strtoupper($_GET['search']['value']) ?? '';
    $columnIndex = $_GET['order'][0]['column'] ?? 0;
    $columnName = strtoupper($_GET['columns'][$columnIndex]['data']);

    $orderDirection = strtoupper($_GET['order'][0]['dir'] ?? 'ASC');
    $data = [];
    // Mapear las columnas para ordenar correctamente
    $columns = ['id_celda', 'destino', 'nro_celda', 'capacidad', 'superficie', 'femenino'];
    $orderColumn = $columns[$columnIndex];

    // Construir la consulta de búsqueda y ordenación
    $where = '';
    if (!empty($searchValue)) {
        // $where = " WHERE destino LIKE %:search% ";
        $where =  " WHERE ( destino LIKE '%{$searchValue}%') ";
    }
    // Obtener el total de registros sin filtros
    $totalRecordsQuery = $conn->query("SELECT COUNT(*) FROM tbl_ac_celdas");
    $totalRecords = $totalRecordsQuery->fetchColumn();

    // Obtener el total de registros filtrados
    $queryFiltrado = "SELECT COUNT(*) FROM tbl_ac_celdas";
    if (!empty($searchValue)) {
        $queryFiltrado = "SELECT COUNT(*) FROM tbl_ac_celdas $where";
    }
    $filteredRecordsQuery = $conn->prepare($queryFiltrado);
    $filteredRecordsQuery->execute();
    $filteredRecords = $filteredRecordsQuery->fetchColumn();
    // $stmt = $conn->query("SELECT id_celda, destino, nro_celda, capacidad, superficie, femenino FROM tbl_ac_celdas");

    $orderBy = "ORDER BY {$columnName} {$orderDirection}";
    // Comprobamos si es -1 es decir mostramos todos. 
    $limit = ($length == -1) ? " " : "OFFSET {$start} ROWS FETCH NEXT {$length} ROWS ONLY";
    // Obtener los datos filtrados y paginados  

    $query = "SELECT id_celda, destino, nro_celda, capacidad, superficie, femenino FROM tbl_ac_celdas $where $orderBy $limit ";
    $stmt = $conn->prepare(
        $query
    );

    if (!empty($searchValue)) {
        //$stmt->bindValue(':search', $searchValue, PDO::PARAM_STR);
    }
    $stmt->execute();
    $data = $stmt->fetchAll();

    // Formatear la respuesta en JSON para DataTables
    echo json_encode([
        'v' => $searchValue,
        'q' => $query,
        'w' => $where,
        'c' => $columnName,
        'draw' => (int)$draw,
        'recordsTotal' => $totalRecords,
        'recordsFiltered' => $filteredRecords,
        'data' => $data
    ]);
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

if ($action === 'delete') {
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
