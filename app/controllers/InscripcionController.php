<?php
include_once __DIR__ . '/../models/Inscripcion.php';
include_once __DIR__ . '/../models/ClaseHorario.php';
include_once __DIR__ . '/../models/Clase.php';
include_once __DIR__ . '/../models/Cliente.php';

$inscripcionModel = new Inscripcion();
$horarioModel = new ClaseHorario();
$claseModel = new Clase();
$clienteModel = new Cliente();

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['USUARIO']) || empty($_SESSION['USUARIO'])) {
    header("Location: ../templates/usuario/login.php?error=Debe+iniciar+sesión");
    exit;
}

// El controlador de inscripciones siempre necesita el ID del horario.
$clase_horario_id = $_GET['clase_horario_id'] ?? $_POST['clase_horario_id'] ?? 0;
if (!$clase_horario_id) {
    header("Location: ClaseController.php?accion=listar&error=Debe+seleccionar+un+horario.");
    exit;
}

// Obtenemos los datos del horario y su clase padre para las vistas
$horario = $horarioModel->obtenerHorarioPorId($clase_horario_id);
if (!$horario) {
    header("Location: ClaseController.php?accion=listar&error=Horario+no+encontrado.");
    exit;
}
$clase = $claseModel->obtenerClasePorId($horario['clase_id']);


$accion = $_GET['accion'] ?? $_POST['accion'] ?? 'gestionar';

switch ($accion) {
    case 'gestionar':
        $inscripciones = $inscripcionModel->obtenerInscripcionesPorHorario($clase_horario_id);
        $clientes = $clienteModel->obtenerClientes(); // Para el dropdown de inscripción
        include_once __DIR__ . '/../templates/inscripcion/gestionar_inscripciones.php';
        break;

    case 'inscribir':
        $cliente_id = $_POST['cliente_id'] ?? 0;
        if (empty($cliente_id)) {
            header("Location: InscripcionController.php?accion=gestionar&clase_horario_id={$clase_horario_id}&error=Debe+seleccionar+un+cliente.");
            exit;
        }

        $resultado = $inscripcionModel->inscribirCliente($clase_horario_id, $cliente_id);

        if ($resultado === true) {
            header("Location: InscripcionController.php?accion=gestionar&clase_horario_id={$clase_horario_id}&success=Cliente+inscrito+correctamente.");
        } else {
            // Si el modelo devolvió un mensaje de error, lo mostramos
            header("Location: InscripcionController.php?accion=gestionar&clase_horario_id={$clase_horario_id}&error=" . urlencode($resultado));
        }
        exit;

    case 'cancelar':
        $inscripcion_id = $_GET['inscripcion_id'] ?? 0;
        if (empty($inscripcion_id)) {
            header("Location: InscripcionController.php?accion=gestionar&clase_horario_id={$clase_horario_id}&error=Inscripción+no+válida.");
            exit;
        }

        if ($inscripcionModel->cancelarInscripcion($inscripcion_id)) {
            header("Location: InscripcionController.php?accion=gestionar&clase_horario_id={$clase_horario_id}&success=Inscripción+cancelada+correctamente.");
        } else {
            header("Location: InscripcionController.php?accion=gestionar&clase_horario_id={$clase_horario_id}&error=Error+al+cancelar+la+inscripción.");
        }
        exit;

    default:
        header("Location: InscripcionController.php?accion=gestionar&clase_horario_id={$clase_horario_id}");
        exit;
}
?>