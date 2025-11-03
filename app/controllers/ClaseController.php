<?php
include_once __DIR__ . '/../models/Clase.php';
include_once __DIR__ . '/../models/Actividad.php';
include_once __DIR__ . '/../models/Entrenador.php';

$claseModel = new Clase();
$actividadModel = new Actividad();
$entrenadorModel = new Entrenador();

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['USUARIO']) || empty($_SESSION['USUARIO'])) {
    header("Location: ../templates/usuario/login.php?error=Debe+iniciar+sesión");
    exit;
}

$accion = $_GET['accion'] ?? $_POST['accion'] ?? 'listar';

switch ($accion) {
    case 'listar':
        $clases = $claseModel->obtenerClases();
        include_once __DIR__ . '/../templates/clase/listar_clases.php';
        break;

    case 'agregar':
        $errores = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre' => trim($_POST['nombre'] ?? ''),
                'actividad_id' => filter_var($_POST['actividad_id'] ?? 0, FILTER_VALIDATE_INT),
                'entrenador_id' => filter_var($_POST['entrenador_id'] ?? 0, FILTER_VALIDATE_INT),
                'capacidad' => filter_var($_POST['capacidad'] ?? 0, FILTER_VALIDATE_INT)
            ];

            if (empty($datos['nombre'])) $errores[] = 'El nombre es obligatorio.';
            if (empty($datos['actividad_id'])) $errores[] = 'Debe seleccionar una actividad.';
            if (empty($datos['entrenador_id'])) $errores[] = 'Debe seleccionar un entrenador.';
            if ($datos['capacidad'] <= 0) $errores[] = 'La capacidad debe ser un número positivo.';

            if (empty($errores)) {
                if ($claseModel->crearClase($datos)) {
                    header("Location: ClaseController.php?accion=listar&success=Clase+creada+correctamente");
                    exit;
                } else {
                    $errores[] = 'Error al crear la clase.';
                }
            }
        }
        
        $actividades = $actividadModel->obtenerActividades();
        $entrenadores = $entrenadorModel->obtenerEntrenadores();
        include_once __DIR__ . '/../templates/clase/agregar_clase.php';
        break;

    case 'editar':
        $id = $_GET['id'] ?? $_POST['id'] ?? 0;
        $errores = [];
        $clase = $claseModel->obtenerClasePorId($id);

        if (!$clase) {
            header("Location: ClaseController.php?accion=listar&error=Clase+no+encontrada");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre' => trim($_POST['nombre'] ?? ''),
                'actividad_id' => filter_var($_POST['actividad_id'] ?? 0, FILTER_VALIDATE_INT),
                'entrenador_id' => filter_var($_POST['entrenador_id'] ?? 0, FILTER_VALIDATE_INT),
                'capacidad' => filter_var($_POST['capacidad'] ?? 0, FILTER_VALIDATE_INT)
            ];

            if (empty($datos['nombre'])) $errores[] = 'El nombre es obligatorio.';
            if (empty($datos['actividad_id'])) $errores[] = 'Debe seleccionar una actividad.';
            if (empty($datos['entrenador_id'])) $errores[] = 'Debe seleccionar un entrenador.';
            if ($datos['capacidad'] <= 0) $errores[] = 'La capacidad debe ser un número positivo.';

            if (empty($errores)) {
                if ($claseModel->actualizarClase($id, $datos)) {
                    header("Location: ClaseController.php?accion=listar&success=Clase+actualizada+correctamente");
                    exit;
                } else {
                    $errores[] = 'Error al actualizar la clase.';
                }
            }
        }

        $actividades = $actividadModel->obtenerActividades();
        $entrenadores = $entrenadorModel->obtenerEntrenadores();
        include_once __DIR__ . '/../templates/clase/editar_clase.php';
        break;

    case 'eliminar':
        $id = $_GET['id'] ?? 0;
        if ($claseModel->eliminarClase($id)) {
            header("Location: ClaseController.php?accion=listar&success=Clase+eliminada+correctamente");
        } else {
            header("Location: ClaseController.php?accion=listar&error=Error+al+dar+de+baja+la+clase");
        }
        exit;

    default:
        header("Location: ClaseController.php?accion=listar");
        exit;
}
?>