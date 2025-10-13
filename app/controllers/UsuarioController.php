<?php
include_once __DIR__ . '/../models/Usuario.php';

$usuario = new Usuario();

// Crear nuevo usuario
if (isset($_POST['accion']) && $_POST['accion'] == 'nuevo_usuario') {
    $dni = $_POST['dni'];
    $password = $_POST['password'];
    
    // Validar que el DNI no exista
    if ($usuario->dniExiste($dni)) {
        header("Location: ../../app/templates/usuario/nuevo_usuario.php?error=El+DNI+ya+está+registrado");
        exit;
    }
    
    // Validar fortaleza de contraseña
    $errores_password = $usuario->validarFortalezaPassword($password);
    if (!empty($errores_password)) {
        $error_msg = implode(". ", $errores_password);
        header("Location: ../../app/templates/usuario/nuevo_usuario.php?error=" . urlencode($error_msg));
        exit;
    }
    
    $ok = $usuario->crearUsuario($_POST['nombre'], $_POST['apellido'], $_POST['email'], $_POST['dni'], $_POST['password'], $_POST['rol']);
    if ($ok) {
        header("Location: ../../app/templates/usuario/login.php?msg=Usuario+creado+correctamente");
    } else {
        header("Location: ../../app/templates/usuario/nuevo_usuario.php?error=Error+al+crear+usuario");
    }
    exit;
}

// Cambiar contraseña
if (isset($_POST['accion']) && $_POST['accion'] == 'cambiar_contrasena') {
    $dni = $_POST['dni'];
    $password_actual = $_POST['password_actual'];
    $nueva_password = $_POST['nueva_password'];
    
    // Validar que la nueva contraseña sea diferente a la actual
    if ($password_actual === $nueva_password) {
        header("Location: ../../app/templates/usuario/cambiar_contrasena.php?error=La+nueva+contraseña+debe+ser+diferente+a+la+actual");
        exit;
    }
    
    // Validar fortaleza de la nueva contraseña
    $errores_password = $usuario->validarFortalezaPassword($nueva_password);
    if (!empty($errores_password)) {
        $error_msg = implode(". ", $errores_password);
        header("Location: ../../app/templates/usuario/cambiar_contrasena.php?error=" . urlencode($error_msg));
        exit;
    }
    
    // Verificar bloqueo por intentos fallidos
    $bloqueo = $usuario->verificarBloqueo($dni);
    if ($bloqueo !== false) {
        header("Location: ../../app/templates/usuario/cambiar_contrasena.php?error=Usuario+bloqueado.+Intente+nuevamente+en+{$bloqueo}+segundos");
        exit;
    }
    
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
        header("Location: ../../app/templates/usuario/cambiar_contrasena.php?error=" . urlencode($login_result));
    }
    exit;
}
