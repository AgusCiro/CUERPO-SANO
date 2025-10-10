<?php


function conectar()
{
    $conexion = null;


    // Datos de conexión
    $host = '127.0.0.1';     // o localhost
    $db   = 'CUERPO_SANO';   // nombre de la base que creaste en phpMyAdmin
    $user = 'root';          // usuario por defecto en XAMPP
    $pwd  = '';              // contraseña vacía por defecto en XAMPP
    $charset = 'utf8';


    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";


    try {
        $conexion = new PDO($dsn, $user, $pwd);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo 'Error al conectar con la base de datos: ' . $e->getMessage();
        exit;
    }


    return $conexion;
}
?>
