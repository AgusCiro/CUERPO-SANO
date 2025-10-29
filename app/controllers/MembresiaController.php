<?php
include_once __DIR__ . '/../models/Membresia.php';
include_once __DIR__ . '/../models/MembresiaTipo.php';
include_once __DIR__ . '/../models/Cliente.php';

$membresia = new Membresia();
$membresiaTipo = new MembresiaTipo();
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
        $filtro = $_GET['filtro'] ?? '';
        $membresias = $membresia->obtenerMembresias($filtro);
        include_once __DIR__ . '/../templates/membresia/listar_membresias.php';
        break;

    case 'agregar':
    case 'asignar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'cliente_id' => $_POST['cliente_id'] ?? '',
                'tipo_id' => $_POST['tipo_id'] ?? '',
                'fecha_inicio' => $_POST['fecha_inicio'] ?? null,
                'fecha_fin' => $_POST['fecha_fin'] ?? null,
                'precio_pagado' => $_POST['precio_pagado'] ?? '',
                'estado' => $_POST['estado'] ?? 'vigente',
                'numero_comprobante' => $_POST['numero_comprobante'] ?? ''
            ];

            if ($membresia->crearMembresia($datos)) {
                header("Location: MembresiaController.php?accion=listar&success=Membresía+creada+correctamente");
                exit;
            } else {
                $errores[] = "Error al crear la membresía";
            }
        }
        
        $clientes = $cliente->obtenerClientes();
        $tipos = $membresiaTipo->obtenerMembresiaTipos();
        include_once __DIR__ . '/../templates/membresia/agregar_membresia.php';
        break;

    case 'editar':
        $id = $_POST['id'] ?? $_GET['id'] ?? 0;
        $membresiaData = $membresia->obtenerMembresiaPorId($id);
        
        if (!$membresiaData) {
            header("Location: MembresiaController.php?accion=listar&error=Membresía+no+encontrada");
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'cliente_id' => $_POST['cliente_id'] ?? '',
                'tipo_id' => $_POST['tipo_id'] ?? '',
                'fecha_inicio' => $_POST['fecha_inicio'] ?? null,
                'fecha_fin' => $_POST['fecha_fin'] ?? null,
                'precio_pagado' => $_POST['precio_pagado'] ?? '',
                'estado' => $_POST['estado'] ?? 'vigente',
                'numero_comprobante' => $_POST['numero_comprobante'] ?? ''
            ];

            if ($membresia->actualizarMembresia($id, $datos)) {
                header("Location: MembresiaController.php?accion=listar&success=Membresía+actualizada+correctamente");
                exit;
            } else {
                $errores[] = "Error al actualizar la membresía";
            }
        }
        
        $clientes = $cliente->obtenerClientes();
        $tipos = $membresiaTipo->obtenerMembresiaTipos();
        include_once __DIR__ . '/../templates/membresia/editar_membresia.php';
        break;

    case 'eliminar':
        $id = $_GET['id'] ?? 0;
        
        if ($membresia->eliminarMembresia($id)) {
            header("Location: MembresiaController.php?accion=listar&success=Membresía+eliminada+correctamente");
        } else {
            header("Location: MembresiaController.php?accion=listar&error=Error+al+eliminar+la+membresía");
        }
        exit;

    case 'listar_tipos':
        $tipos = $membresiaTipo->obtenerMembresiaTipos();
        include_once __DIR__ . '/../templates/membresia/listar_tipos.php';
        break;

    case 'agregar_tipo':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre' => $_POST['nombre'] ?? '',
                'duracion' => $_POST['duracion'] ?? '',
                'precio' => $_POST['precio'] ?? '',
                'descripcion' => $_POST['descripcion'] ?? ''
            ];

            if ($membresiaTipo->crearMembresiaTipo($datos)) {
                header("Location: MembresiaController.php?accion=listar_tipos&success=Tipo+de+membresía+creado+correctamente");
                exit;
            } else {
                $errores[] = "Error al crear el tipo de membresía";
            }
        }
        include_once __DIR__ . '/../templates/membresia/agregar_tipo.php';
        break;

    case 'editar_tipo':
        $id = $_POST['id'] ?? $_GET['id'] ?? 0;
        $tipoData = $membresiaTipo->obtenerMembresiaTipoPorId($id);

        if (!$tipoData) {
            header("Location: MembresiaController.php?accion=listar_tipos&error=Tipo+de+membresía+no+encontrado");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre' => $_POST['nombre'] ?? '',
                'duracion' => $_POST['duracion'] ?? '',
                'precio' => $_POST['precio'] ?? '',
                'descripcion' => $_POST['descripcion'] ?? '',
                'activo' => $_POST['activo'] ?? '1'
            ];

            if ($membresiaTipo->actualizarMembresiaTipo($id, $datos)) {
                header("Location: MembresiaController.php?accion=listar_tipos&success=Tipo+de+membresía+actualizado+correctamente");
                exit;
            } else {
                $errores[] = "Error al actualizar el tipo de membresía";
            }
        }
        include_once __DIR__ . '/../templates/membresia/editar_tipo.php';
        break;

    case 'eliminar_tipo':
        $id = $_GET['id'] ?? 0;
        
        if ($membresiaTipo->eliminarMembresiaTipo($id)) {
            header("Location: MembresiaController.php?accion=listar_tipos&success=Tipo+de+membresía+eliminado+correctamente");
        } else {
            header("Location: MembresiaController.php?accion=listar_tipos&error=Error+al+eliminar+el+tipo+de+membresía");
        }
        exit;

    default:
        header("Location: MembresiaController.php?accion=listar");
        exit;
}
?>