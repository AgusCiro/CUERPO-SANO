
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
error_reporting(E_ALL); 
header("Content-Type: text/html; charset=utf-8");
ini_set("display_errors", 1);


include_once "../models/conexion.php";
include_once "../models/datos.php";

include_once "../models/persona.php";
$datos=new Datos();
$people=new Persona();

$accion=(isset($_POST['accion']))  ? $_POST['accion']  : 0;

switch($accion) {
    
    case 'guardar_dato':
        $datos->setNombre((isset($_POST['nombre']))  ? $_POST['nombre']  : 0);
        $datos->setCalle((isset($_POST['calle']))  ? $_POST['calle']  : 0);
        $datos->setAltura((isset($_POST['altura']))  ? $_POST['altura']  : 0);
        $datos->setPiso((isset($_POST['piso']))  ? $_POST['piso']  : 0);
        $datos->setDepartamento((isset($_POST['departamento']))  ? $_POST['departamento']  : 0);
        $datos->guardarDatos();
        
    break;

   case 'guardar':
        $people->setNombre((isset($_POST['nombre']))  ? $_POST['nombre']  : 0);
        $people->setApellido((isset($_POST['apellido']))  ? $_POST['apellido']  : 0);
        $people->setEdad((isset($_POST['edad']))  ? $_POST['edad']  : 0);
        $people->setFechanac((isset($_POST['fechanac']))  ? $_POST['fechanac']  : 0);
        $people->setNacionalidad((isset($_POST['nacionalidad']))  ? $_POST['nacionalidad']  : 0);
        $people->guardarPersona();
    
    break;
    /*
    case 'guardar_buque':
        $datos->setNombre((isset($_POST['nombre']))  ? $_POST['nombre']  : 0);
        $datos->setCalle((isset($_POST['calle']))  ? $_POST['calle']  : 0);
        $datos->setAltura((isset($_POST['altura']))  ? $_POST['altura']  : 0);
        $datos->setPiso((isset($_POST['piso']))  ? $_POST['piso']  : 0);
        $datos->setDepartamento((isset($_POST['departamento']))  ? $_POST['departamento']  : 0);
        $datos->guardarDatos();
    */
        
    break;   
    case 'editar_dato':
        $datos->setId((isset($_POST['id']))  ? $_POST['id']  : 0);
        $datos->setNombre((isset($_POST['nombre']))  ? $_POST['nombre']  : 0);
        $datos->setCalle((isset($_POST['calle']))  ? $_POST['calle']  : 0);
        $datos->setAltura((isset($_POST['altura']))  ? $_POST['altura']  : 0);
        $datos->setPiso((isset($_POST['piso']))  ? $_POST['piso']  : 0);
        $datos->setDepartamento((isset($_POST['dep']))  ? $_POST['dep']  : 0);
        $datos->editarDatos();
        
    break;
    
    break;   
    case 'editar_persona':
        $people->setnombre((isset($_POST['nombre']))  ? $_POST['nombre']  : 0);
        $people->setapellido((isset($_POST['apellido']))  ? $_POST['apellido']  : 0);
        $people->setedad((isset($_POST['edad']))  ? $_POST['edad']  : 0);
        $people->setfechanac((isset($_POST['fechanac']))  ? $_POST['fechanac']  : 0);
        $people->setnacionalidad((isset($_POST['nacionalidad']))  ? $_POST['nacionalidad']  : 0);
        $people->editarPersona();
        
    break;
    case 'editar_persona':
        $people->mostrarPersona();
   break;
    case 'editar_datos':
        $datos->mostrarDatos();
   break;

    case 'eliminar_dato':
       // var_dump($_POST['id']);
        
        $r = (isset($_POST['id']))  ? $_POST['id']  : 0;
        $datos->setId($r);
        $datos->eliminarDatos();
        
    case 'eliminar_persona':
        // var_dump($_POST['id']);
         
         $r = (isset($_POST['id_p']))  ? $_POST['id_p']  : 0;
         $people->setId_p($r);
         $people->eliminarPersona();
        
   break;
   case 'listar_datos':
        $datos->mostrarDatos();
   break;
   case 'listar_persona':
    $people->mostrarPersona();
   break;

   default:
        $vuelto = array(
        'res' => 'ko',
        'txt' => 'pedido mal formado, falta acción'
            );
        $salida = json_encode($vuelto);
        error_log($salida);
        echo $salida;
        break;
   
   
}
?>