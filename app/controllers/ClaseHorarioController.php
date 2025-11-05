<?php
include_once __DIR__ . '/../models/ClaseHorario.php';
include_once __DIR__ . '/../models/Clase.php';
include_once __DIR__ . '/../models/Inscripcion.php';

$horarioModel = new ClaseHorario();
$claseModel = new Clase();

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['USUARIO']) || empty($_SESSION['USUARIO'])) {
    header("Location: ../templates/usuario/login.php?error=Debe+iniciar+sesión");
    exit;
}

// El controlador de horarios siempre necesita el ID de la clase.
$clase_id = $_GET['clase_id'] ?? $_POST['clase_id'] ?? 0;
if (!$clase_id) {
    header("Location: ClaseController.php?accion=listar&error=Debe+seleccionar+una+clase+para+gestionar+sus+horarios.");
    exit;
}

// Obtenemos los datos de la clase padre para mostrar en las vistas
$clase = $claseModel->obtenerClasePorId($clase_id);
if (!$clase) {
    header("Location: ClaseController.php?accion=listar&error=Clase+no+encontrada.");
    exit;
}

$accion = $_GET['accion'] ?? $_POST['accion'] ?? 'listar';

switch ($accion) {
    case 'listar':
        $horarios = $horarioModel->obtenerHorariosPorClaseId($clase_id);
        include_once __DIR__ . '/../templates/clase_horario/listar_horarios.php';
        break;

    case 'agregar':
        $errores = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'clase_id' => $clase_id,
                'fecha_inicio' => $_POST['fecha_inicio'] ?? '',
                'fecha_fin' => $_POST['fecha_fin'] ?? '',
                'ubicacion' => trim($_POST['ubicacion'] ?? ''),
                'cupo' => filter_var($_POST['cupo'] ?? 0, FILTER_VALIDATE_INT)
            ];

            if (empty($datos['fecha_inicio']) || empty($datos['fecha_fin'])) $errores[] = 'Las fechas de inicio y fin son obligatorias.';
            if ($datos['cupo'] <= 0) $errores[] = 'El cupo debe ser un número positivo.';
            if ($horarioModel->verificarSuperposicion($clase_id, $datos['fecha_inicio'], $datos['fecha_fin'])) {
                $errores[] = 'El horario se superpone con otro ya existente para esta clase.';
            }

            if (empty($errores)) {
                if ($horarioModel->crearHorario($datos)) {
                    header("Location: ClaseHorarioController.php?clase_id={$clase_id}&success=Horario+creado+correctamente");
                    exit;
                } else {
                    $errores[] = 'Error al crear el horario.';
                }
            }
        }
        include_once __DIR__ . '/../templates/clase_horario/agregar_horario.php';
        break;

    case 'editar':
        $id = $_GET['id'] ?? $_POST['id'] ?? 0;
        $errores = [];
        $horario = $horarioModel->obtenerHorarioPorId($id);

        if (!$horario) {
            header("Location: ClaseHorarioController.php?clase_id={$clase_id}&error=Horario+no+encontrado");
            exit;
        }

        // Se necesita el modelo de inscripciones para validar el cupo
        $inscripcionModel = new Inscripcion();
        $inscritos = $inscripcionModel->contarInscripcionesPorHorario($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'fecha_inicio' => $_POST['fecha_inicio'] ?? '',
                'fecha_fin' => $_POST['fecha_fin'] ?? '',
                'ubicacion' => trim($_POST['ubicacion'] ?? ''),
                'cupo' => filter_var($_POST['cupo'] ?? 0, FILTER_VALIDATE_INT)
            ];

            if (empty($datos['fecha_inicio']) || empty($datos['fecha_fin'])) $errores[] = 'Las fechas de inicio y fin son obligatorias.';
            if ($datos['cupo'] <= 0) $errores[] = 'El cupo debe ser un número positivo.';
            
            // Validar que el nuevo cupo no sea menor a los ya inscritos
            if ($datos['cupo'] < $inscritos) {
                $errores[] = "El cupo no puede ser menor que la cantidad de clientes ya inscritos ({$inscritos}).";
            }

            if ($horarioModel->verificarSuperposicion($clase_id, $datos['fecha_inicio'], $datos['fecha_fin'], $id)) {
                $errores[] = 'El horario se superpone con otro ya existente para esta clase.';
            }

            if (empty($errores)) {
                // Se pasa el número de inscritos para recalcular el cupo restante
                if ($horarioModel->actualizarHorario($id, $datos, $inscritos)) {
                    header("Location: ClaseHorarioController.php?clase_id={$clase_id}&success=Horario+actualizado+correctamente");
                    exit;
                } else {
                    $errores[] = 'Error al actualizar el horario.';
                }
            }
        }
        include_once __DIR__ . '/../templates/clase_horario/editar_horario.php';
        break;

    case 'eliminar':
        $id = $_GET['id'] ?? 0;
        if ($horarioModel->eliminarHorario($id)) {
            header("Location: ClaseHorarioController.php?clase_id={$clase_id}&success=Horario+eliminado+correctamente");
        } else {
            header("Location: ClaseHorarioController.php?clase_id={$clase_id}&error=Error+al+eliminar+el+horario");
        }
        exit;

    case 'restablecer':
        $id = $_GET['id'] ?? 0;
        if ($horarioModel->restablecerHorario($id)) {
            header("Location: ClaseHorarioController.php?clase_id={$clase_id}&success=Horario+restablecido+correctamente.+Las+inscripciones+han+sido+eliminadas+y+el+cupo+restaurado.");
        } else {
            header("Location: ClaseHorarioController.php?clase_id={$clase_id}&error=Error+al+restablecer+el+horario");
        }
        exit;

    default:
        header("Location: ClaseHorarioController.php?accion=listar&clase_id={$clase_id}");
        exit;
}
?>