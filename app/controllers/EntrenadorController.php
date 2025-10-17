<?php
include_once __DIR__ . '/../models/Entrenador.php';

$entrenador = new Entrenador();

// Verificar que el usuario esté logueado
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['USUARIO']) || empty($_SESSION['USUARIO'])) {
    header("Location: ../templates/usuario/login.php?error=Debe+iniciar+sesión");
    exit;
}

// Obtener la acción
$accion = $_GET['accion'] ?? $_POST['accion'] ?? 'listar';

switch ($accion) {
    case 'listar':
        // Obtener filtro de búsqueda
        $filtro = $_GET['filtro'] ?? '';
        $entrenadores = $entrenador->obtenerEntrenadores($filtro);

        // Incluir la vista de listado
        include_once __DIR__ . '/../templates/entrenador/listar_entrenadores.php';
        break;

    case 'ver':
        $id = $_GET['id'] ?? 0;
        $entrenadorData = $entrenador->obtenerEntrenadorPorId($id);
        
        if (!$entrenadorData) {
            header("Location: EntrenadorController.php?accion=listar&error=Entrenador+no+encontrado");
            exit;
        }
        
        include_once __DIR__ . '/../templates/entrenador/ver_entrenador.php';
        break;

    case 'agregar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar datos
            $datos = [
                'nombre' => trim($_POST['nombre'] ?? ''),
                'apellido' => trim($_POST['apellido'] ?? ''),
                'dni' => trim($_POST['dni'] ?? ''),
                'telefono' => trim($_POST['telefono'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
            ];

            // Validaciones
            $errores = [];
            
            if (empty($datos['nombre'])) {
                $errores[] = "El nombre es obligatorio";
            }
            
            if (empty($datos['apellido'])) {
                $errores[] = "El apellido es obligatorio";
            }
            
            if (empty($errores)) {
                if ($entrenador->crearEntrenador($datos)) {
                    header("Location: EntrenadorController.php?accion=listar&success=Entrenador+creado+correctamente");
                    exit;
                } else {
                    $errores[] = "Error al crear el entrenador";
                }
            }
        }
        
        include_once __DIR__ . '/../templates/entrenador/agregar_entrenador.php';
        break;

    case 'editar':
        $id = $_GET['id'] ?? $_POST['id'] ?? 0;
        $entrenadorData = $entrenador->obtenerEntrenadorPorId($id);
        
        if (!$entrenadorData) {
            header("Location: EntrenadorController.php?accion=listar&error=Entrenador+no+encontrado");
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar datos
            $datos = [
                'nombre' => trim($_POST['nombre'] ?? ''),
                'apellido' => trim($_POST['apellido'] ?? ''),
                'dni' => trim($_POST['dni'] ?? ''),
                'telefono' => trim($_POST['telefono'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
            ];

            // Validaciones
            $errores = [];
            
            if (empty($datos['nombre'])) {
                $errores[] = "El nombre es obligatorio";
            }
            
            if (empty($datos['apellido'])) {
                $errores[] = "El apellido es obligatorio";
            }
            
            if (empty($errores)) {
                if ($entrenador->actualizarEntrenador($id, $datos)) {
                    header("Location: EntrenadorController.php?accion=listar&success=Entrenador+actualizado+correctamente");
                    exit;
                } else {
                    $errores[] = "Error al actualizar el entrenador";
                }
            }
        }
        
        include_once __DIR__ . '/../templates/entrenador/editar_entrenador.php';
        break;

    case 'eliminar':
        $id = $_GET['id'] ?? 0;
        
        if ($entrenador->eliminarEntrenador($id)) {
            header("Location: EntrenadorController.php?accion=listar&success=Entrenador+eliminado+correctamente");
        } else {
            header("Location: EntrenadorController.php?accion=listar&error=Error+al+eliminar+el+entrenador");
        }
        exit;

    default:
        header("Location: EntrenadorController.php?accion=listar");
        exit;
}
?>
