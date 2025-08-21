<?php 

class persona{
    private  $id_p;
    private $nombre;
    private  $apellido;
    private  $edad;
    private  $fechanac;
    private  $nacionalidad;
    private  $conPDO;
    

    public function __construct(){//contructor de la clase dato
    
        $this->id_p = 0;
        $this->nombre = 0;
        $this->apellido = 0;
        $this->edad = 0;
        $this->fechanac = 0;
        $this->nacionalidad = 0;
        $this->conPDO = conectar();
    }
    
    public function getId_p()
    {
        return $this->id_p;
    }

    public function getNombre()
    {
        return $this->nombre;
    }


    public function getapellido()
    {
        return $this->apellido;
    }
    public function getEdad()
    {
        return $this->edad;
    }

    public function getfechanac()
    {
        return $this->fechanac;
    }

    public function getnacionalidad()
    {
        return $this->nacionalidad;
    }





    public function setId_p($id_p)
    {
        $this->id_p = $id_p;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    
    public function setapellido($apellido)
    {
        $this->apellido = $apellido;
    }
    public function setEdad($edad)
    {
        $this->edad = $edad;
    }
    public function setfechanac($fechanac)
    {
        $this->fechanac = $fechanac;
    }

    public function setNacionalidad($nacionalidad)
    {
        $this->nacionalidad = $nacionalidad;

    }    

  
    public function mostrarPersona(){//muestra todo  los datos activos estado=0
        $query="SELECT
 
            id_p,
            nombre,
            apellido,
            edad,
            fecha_nacimiento,
            nacionalidad
            
        FROM
            nuevas_personas
        WHERE
            estado = 1";

        $this->conPDO = conectar();
        $vuelto = array();
        $aux = array();

        $rows = $this->conPDO->query($query);
        if( $rows ){


            foreach($rows as $row)
            {
                $a = array(
                    'id_p'=> $row['ID_P'],
                    'nombre'=> $row['NOMBRE'],
                    'apellido'=> $row['APELLIDO'],
                    'edad'=> $row['EDAD'],
                    'fechanac'=> $row['FECHA_NACIMIENTO'],
                    'nacionalidad'=> $row['NACIONALIDAD']
                );
                $aux[] = $a;
            } 
            $vuelto['datos'] = $aux;
            $vuelto['res'] = 'ok';
        }
        echo json_encode($vuelto);
        
    }
    
    public function guardarPersona(){//guarda los datos ingresado 
        try{
            $vuelto=array();
            $query = "INSERT INTO nuevas_personas (NOMBRE, APELLIDO, EDAD, FECHA_NACIMIENTO, NACIONALIDAD)
                                     VALUES(?,?,?,to_date('$this->fechanac','dd/mm/yyyy'),?)";
        
            $sentencia = $this->conPDO->prepare($query);
            $sentencia->bindParam(1, $this->nombre, PDO::PARAM_STR);
            $sentencia->bindParam(2, $this->apellido, PDO::PARAM_STR);
            $sentencia->bindParam(3, $this->edad, PDO::PARAM_INT);
           // $sentencia->bindParam(4, $this->fechanac, PDO::PARAM_STR);
            $sentencia->bindParam(4, $this->nacionalidad, PDO::PARAM_STR);
            $sentencia->execute();
            $vuelto['res'] = 'ok';
            $salida = json_encode($vuelto);
        
        }catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
            $vuelto['res'] = 'ko';
        }
        echo json_encode($vuelto);
    }
    public function editarPersona(){
        
        try {

            $query = "UPDATE nuevas_personas SET nombre=?, apellido=?, edad=?, FECHA_NACIMIENTO= to_date('$this->fechanac','dd/mm/yyyy'), nacionalidad=? WHERE id_p=?";
           
            $sentencia = $this->conPDO->prepare($query);
            $sentencia->bindParam(1, $this->nombre, PDO::PARAM_STR);
            $sentencia->bindParam(2, $this->apellido, PDO::PARAM_STR);
            $sentencia->bindParam(3, $this->edad, PDO::PARAM_INT);
           // $sentencia->bindParam(4, $this->fechanac, PDO::PARAM_STR);
            $sentencia->bindParam(4, $this->nacionalidad, PDO::PARAM_STR);
            $sentencia->bindParam(5, $this->id_p, PDO::PARAM_INT);
            $sentencia->execute();
            $vuelto['res'] = 'ok';
            $salida = json_encode($vuelto);

        }catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
            $vuelto['res'] = 'ko';
        }
        echo json_encode($vuelto);
    }
    public function eliminarPersona(){
  
      try {
            $estado=0;
            $query = "update  nuevas_personas set ESTADO=?
                       where ID_P=?";
            $sentencia = $this->conPDO->prepare($query);
            $sentencia->bindParam(1, $estado, PDO::PARAM_INT);
            $sentencia->bindParam(2, $this->id_p, PDO::PARAM_INT);
            $sentencia->execute();
            $vuelto['res'] = 'ok';
            $salida = json_encode($vuelto);
        }catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
            $vuelto['res'] = 'ko';
        }
        echo json_encode($vuelto);
    }
}
?>
