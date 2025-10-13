<?php
include_once __DIR__ . '/../models/Cliente.php';

$cliente = new Cliente();

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
        $clientes = $cliente->obtenerClientes($filtro);
        
        // Incluir la vista de listado
        include_once __DIR__ . '/../templates/cliente/listar_clientes.php';
        break;

    case 'ver':
        $id = $_GET['id'] ?? 0;
        $clienteData = $cliente->obtenerClientePorId($id);
        
        if (!$clienteData) {
            header("Location: ClienteController.php?accion=listar&error=Cliente+no+encontrado");
            exit;
        }
        
        include_once __DIR__ . '/../templates/cliente/ver_cliente.php';
        break;

    case 'agregar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar datos
            $datos = [
                'usuario_id' => $_POST['usuario_id'] ?? null,
                'codigo_barcode' => $_POST['codigo_barcode'] ?? '',
                'nombre' => trim($_POST['nombre'] ?? ''),
                'apellido' => trim($_POST['apellido'] ?? ''),
                'direccion' => trim($_POST['direccion'] ?? ''),
                'telefono' => trim($_POST['telefono'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'fecha_nacimiento' => $_POST['fecha_nacimiento'] ?? null,
                'tipo_descuento' => $_POST['tipo_descuento'] ?? 'none',
                'estado' => $_POST['estado'] ?? 'activo'
            ];

            // Validaciones
            $errores = [];
            
            if (empty($datos['nombre'])) {
                $errores[] = "El nombre es obligatorio";
            }
            
            if (empty($datos['apellido'])) {
                $errores[] = "El apellido es obligatorio";
            }
            
            if (!empty($datos['codigo_barcode']) && $cliente->codigoBarcodeExiste($datos['codigo_barcode'])) {
                $errores[] = "El código de barras ya existe";
            }
            
            if (empty($errores)) {
                // Generar código de barras si no se proporcionó
                if (empty($datos['codigo_barcode'])) {
                    $datos['codigo_barcode'] = $cliente->generarCodigoBarcode();
                }
                
                if ($cliente->crearCliente($datos)) {
                    header("Location: ClienteController.php?accion=listar&success=Cliente+creado+correctamente");
                    exit;
                } else {
                    $errores[] = "Error al crear el cliente";
                }
            }
        }
        
        // Obtener usuarios disponibles
        $usuariosDisponibles = $cliente->obtenerUsuariosDisponibles();
        include_once __DIR__ . '/../templates/cliente/agregar_cliente.php';
        break;

    case 'editar':
        $id = $_GET['id'] ?? 0;
        $clienteData = $cliente->obtenerClientePorId($id);
        
        if (!$clienteData) {
            header("Location: ClienteController.php?accion=listar&error=Cliente+no+encontrado");
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar datos
            $datos = [
                'usuario_id' => $_POST['usuario_id'] ?? null,
                'codigo_barcode' => $_POST['codigo_barcode'] ?? '',
                'nombre' => trim($_POST['nombre'] ?? ''),
                'apellido' => trim($_POST['apellido'] ?? ''),
                'direccion' => trim($_POST['direccion'] ?? ''),
                'telefono' => trim($_POST['telefono'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'fecha_nacimiento' => $_POST['fecha_nacimiento'] ?? null,
                'tipo_descuento' => $_POST['tipo_descuento'] ?? 'none',
                'estado' => $_POST['estado'] ?? 'activo'
            ];

            // Validaciones
            $errores = [];
            
            if (empty($datos['nombre'])) {
                $errores[] = "El nombre es obligatorio";
            }
            
            if (empty($datos['apellido'])) {
                $errores[] = "El apellido es obligatorio";
            }
            
            if (!empty($datos['codigo_barcode']) && $cliente->codigoBarcodeExiste($datos['codigo_barcode'], $id)) {
                $errores[] = "El código de barras ya existe";
            }
            
            if (empty($errores)) {
                if ($cliente->actualizarCliente($id, $datos)) {
                    header("Location: ClienteController.php?accion=listar&success=Cliente+actualizado+correctamente");
                    exit;
                } else {
                    $errores[] = "Error al actualizar el cliente";
                }
            }
        }
        
        // Obtener usuarios disponibles
        $usuariosDisponibles = $cliente->obtenerUsuariosDisponibles();
        include_once __DIR__ . '/../templates/cliente/editar_cliente.php';
        break;

    case 'eliminar':
        $id = $_GET['id'] ?? 0;
        
        if ($cliente->eliminarCliente($id)) {
            header("Location: ClienteController.php?accion=listar&success=Cliente+eliminado+correctamente");
        } else {
            header("Location: ClienteController.php?accion=listar&error=Error+al+eliminar+el+cliente");
        }
        exit;

    case 'ajax_usuarios':
        // Endpoint AJAX para obtener usuarios disponibles
        header('Content-Type: application/json');
        $usuarios = $cliente->obtenerUsuariosDisponibles();
        echo json_encode($usuarios);
        exit;

    case 'ajax_estadisticas':
        // Endpoint AJAX para obtener estadísticas
        header('Content-Type: application/json');
        $estadisticas = $cliente->obtenerEstadisticas();
        echo json_encode($estadisticas);
        exit;

    default:
        header("Location: ClienteController.php?accion=listar");
        exit;
}
?>
