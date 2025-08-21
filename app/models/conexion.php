<?php

function conectar()
{
    $conexion = null;
    $dns= 'odbc:gebipadesa';
    $db= '???';
    $user='gebipa';
    $pwd='gebipa';
    
    try
    {
        $conexion = new PDO($dns,$user,$pwd);
    }
    catch(PDOException $e)
    {
        echo 'Error al conectar con la bd'.$e;
        exit;
    }
    return $conexion;
}
