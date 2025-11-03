<?php
include_once __DIR__ . '/../models/Actividad.php';

$actividadModel = new Actividad();

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
        $actividades = $actividadModel->obtenerActividades();
        include_once __DIR__ . '/../templates/actividad/listar_actividades.php';
        break;

    case 'agregar':
        $errores = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre' => trim($_POST['nombre'] ?? ''),
                'descripcion' => trim($_POST['descripcion'] ?? ''),
                'duracion_minutos' => filter_var($_POST['duracion_minutos'] ?? 0, FILTER_VALIDATE_INT),
                'intensidad' => trim($_POST['intensidad'] ?? '')
            ];

            if (empty($datos['nombre'])) {
                $errores[] = 'El nombre es obligatorio.';
            }
            if ($datos['duracion_minutos'] === false || $datos['duracion_minutos'] <= 0) {
                $errores[] = 'La duración debe ser un número positivo.';
            }

            if (empty($errores)) {
                if ($actividadModel->crearActividad($datos)) {
                    header("Location: ActividadController.php?accion=listar&success=Actividad+creada+correctamente");
                    exit;
                } else {
                    $errores[] = 'Error al crear la actividad.';
                }
            }
        }
        include_once __DIR__ . '/../templates/actividad/agregar_actividad.php';
        break;

    case 'editar':
        $id = $_GET['id'] ?? $_POST['id'] ?? 0;
        $errores = [];
        $actividad = $actividadModel->obtenerActividadPorId($id);

        if (!$actividad) {
            header("Location: ActividadController.php?accion=listar&error=Actividad+no+encontrada");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre' => trim($_POST['nombre'] ?? ''),
                'descripcion' => trim($_POST['descripcion'] ?? ''),
                'duracion_minutos' => filter_var($_POST['duracion_minutos'] ?? 0, FILTER_VALIDATE_INT),
                'intensidad' => trim($_POST['intensidad'] ?? '')
            ];

            if (empty($datos['nombre'])) {
                $errores[] = 'El nombre es obligatorio.';
            }
            if ($datos['duracion_minutos'] === false || $datos['duracion_minutos'] <= 0) {
                $errores[] = 'La duración debe ser un número positivo.';
            }

            if (empty($errores)) {
                if ($actividadModel->actualizarActividad($id, $datos)) {
                    header("Location: ActividadController.php?accion=listar&success=Actividad+actualizada+correctamente");
                    exit;
                } else {
                    $errores[] = 'Error al actualizar la actividad.';
                }
            }
        }
        include_once __DIR__ . '/../templates/actividad/editar_actividad.php';
        break;

    case 'eliminar':
        $id = $_GET['id'] ?? 0;
        if ($actividadModel->eliminarActividad($id)) {
            header("Location: ActividadController.php?accion=listar&success=Actividad+eliminada+correctamente");
        } else {
            header("Location: ActividadController.php?accion=listar&error=Error+al+eliminar+la+actividad");
        }
        exit;

    default:
        header("Location: ActividadController.php?accion=listar");
        exit;
}
?>