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
        // Mensaje amigable (debug): lo mostramos en rojo
        echo "<p style='color:red; font-weight:bold;'>" . htmlspecialchars($resultado) . "</p>";
    }
} else {
    http_response_code(405);
    echo "Método no permitido.";
}
