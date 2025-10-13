<?php
include_once __DIR__ . '/../models/Usuario.php';

$usuario = new Usuario();

// Crear nuevo usuario
if (isset($_POST['accion']) && $_POST['accion'] == 'nuevo_usuario') {
    $ok = $usuario->crearUsuario($_POST['nombre'], $_POST['apellido'], $_POST['email'], $_POST['dni'], $_POST['password'], $_POST['rol']);
    if ($ok) {
        header("Location: ../../app/templates/usuario/login.php?msg=Usuario+creado+correctamente");
    } else {
        echo "Error al crear usuario.";
    }
    exit;
}

// Cambiar contraseña
if (isset($_POST['accion']) && $_POST['accion'] == 'cambiar_contrasena') {
    $dni = $_POST['dni'];
    $password_actual = $_POST['password_actual'];
    $nueva_password = $_POST['nueva_password'];
    
    // Validar que la contraseña actual sea correcta
    $login_result = $usuario->login($dni, $password_actual);
    
    if ($login_result === true) {
        // La contraseña actual es correcta, proceder con el cambio
        $ok = $usuario->cambiarContrasena($dni, $nueva_password);
        if ($ok) {
            header("Location: ../../app/templates/usuario/login.php?msg=Contraseña+cambiada+con+éxito");
        } else {
            header("Location: ../../app/templates/usuario/cambiar_contrasena.php?error=Error+al+cambiar+contraseña");
        }
    } else {
        // La contraseña actual es incorrecta
        header("Location: ../../app/templates/usuario/cambiar_contrasena.php?error=Contraseña+actual+incorrecta");
    }
    exit;
}
