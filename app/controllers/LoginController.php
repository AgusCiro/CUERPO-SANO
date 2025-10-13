<?php
// controllers/LoginController.php
require_once __DIR__ . '/../models/Usuario.php';

// mostrar errores en desarrollo (quitar en producción)
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni = $_POST['dni'] ?? '';
    $password = $_POST['password'] ?? '';

    $usuario = new Usuario();
    $resultado = $usuario->login($dni, $password);

    if ($resultado === true) {
        header("Location: ../templates/dashboard.php");
        exit;
    } else {
        // Redirigir con mensaje de error
        $error_msg = urlencode($resultado);
        header("Location: ../templates/usuario/login.php?error=" . $error_msg);
        exit;
    }
} else {
    http_response_code(405);
    echo "Método no permitido.";
}
