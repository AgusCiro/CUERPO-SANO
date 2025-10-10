<?php
include_once __DIR__ . '/../models/Usuario.php';

$usuario = new Usuario();

// Crear nuevo usuario
if (isset($_POST['accion']) && $_POST['accion'] == 'nuevo_usuario') {
    $ok = $usuario->crearUsuario($_POST['nombre'], $_POST['apellido'], $_POST['email'], $_POST['dni'], $_POST['password'], $_POST['rol']);
    if ($ok) {
        header("Location: ../../app/templates/usuarios/login.php?msg=Usuario+creado+correctamente");
    } else {
        echo "Error al crear usuario.";
    }
    exit;
}

// Cambiar contraseña
if (isset($_POST['accion']) && $_POST['accion'] == 'cambiar_contrasena') {
    $ok = $usuario->cambiarContrasena($_POST['dni'], $_POST['nueva_password']);
    if ($ok) {
        header("Location: ../../app/templates/usuarios/login.php?msg=Contraseña+cambiada+con+éxito");
    } else {
        echo "Error al cambiar contraseña.";
    }
    exit;
}
