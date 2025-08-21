<?php 
class Datos{
    private  $id;
    private $nombre;
    private  $calle;
    private  $altura;
    private  $piso;
    private  $departamento;
    private  $conPDO;
    

    public function __construct(){//contructor de la clase dato
    
        $this->id = 0;
        $this->nombre = 0;
        $this->calle = 0;
        $this->altura = 0;
        $this->piso = 0;
        $this->departamento = 0;
        $this->conPDO = conectar();
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }


    public function getCalle()
    {
        return $this->calle;
    }

    public function getAltura()
    {
        return $this->altura;
    }

    public function getPiso()
    {
        return $this->piso;
    }


    public function getDepartamento()
    {
        return $this->departamento;
    }



    public function setId($id)
    {
        $this->id = $id;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    
    public function setCalle($calle)
    {
        $this->calle = $calle;
    }
    public function setAltura($altura)
    {
        $this->altura = $altura;
    }

    public function setPiso($piso)
    {
        $this->piso = $piso;

    }    public function setDepartamento($departamento)
    {
        $this->departamento = $departamento;
    }

    public function mostrarDatos(){//muestra todo  los datos activos estado=0
        $query="SELECT
    id,
    nombre,
    calle,
    altura,
    piso,
    departamento
FROM
    nuevos_datos
WHERE
    estado = 1";
        // $this->conPDO = conectar();
        $vuelto = array();
        $aux = array();

        $rows = $this->conPDO->query($query);
        if( $rows ){
            foreach($rows as $row)
            {
                $a = array(
                    'id'=> $row['ID'],
                    'nombre'=> $row['NOMBRE'],
                    'calle'=> $row['CALLE'],
                    'altura'=> $row['ALTURA'],
                    'piso'=> $row['PISO'],
                    'dep'=> $row['DEPARTAMENTO']
                );
                $aux[] = $a;
            } 
            $vuelto['datos'] = $aux;
            $vuelto['res'] = 'ok';
        }
        echo json_encode($vuelto);
        
    }
    
    public function guardarDatos(){//guarda los datos ingresado 
        try{
            $vuelto=array();
            $query = "INSERT INTO nuevos_datos (nombre, calle, altura, piso, departamento)
                                     VALUES(?,?,?,?,?)";
        
            $sentencia = $this->conPDO->prepare($query);
            $sentencia->bindParam(1, $this->nombre, PDO::PARAM_STR);
            $sentencia->bindParam(2, $this->calle, PDO::PARAM_STR);
            $sentencia->bindParam(3, $this->altura, PDO::PARAM_INT);
            $sentencia->bindParam(4, $this->piso, PDO::PARAM_INT);
            $sentencia->bindParam(5, $this->departamento, PDO::PARAM_STR);
            $sentencia->execute();
            $vuelto['res'] = 'ok';
            $salida = json_encode($vuelto);
        
        }catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
            $vuelto['res'] = 'ko';
        }
        echo json_encode($vuelto);
    }
    public function editarDatos(){
        
        try {

            $query = "update  nuevos_datos set nombre=?,calle=?,altura=?, piso=?, departamento=?
                       where id=?";
           
            $sentencia = $this->conPDO->prepare($query);
            $sentencia->bindParam(1, $this->nombre, PDO::PARAM_STR);
            $sentencia->bindParam(2, $this->calle, PDO::PARAM_STR);
            $sentencia->bindParam(3, $this->altura, PDO::PARAM_INT);
            $sentencia->bindParam(4, $this->piso, PDO::PARAM_INT);
            $sentencia->bindParam(5, $this->departamento, PDO::PARAM_STR);
            $sentencia->bindParam(6, $this->id, PDO::PARAM_INT);
            $sentencia->execute();
            $vuelto['res'] = 'ok';
            $salida = json_encode($vuelto);

        }catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
            $vuelto['res'] = 'ko';
        }
        echo json_encode($vuelto);
    }
    public function eliminarDatos(){
        try {
            $estado=0;
            $query = "update  nuevos_datos set ESTADO=?
                       where id=?";
           
            $sentencia = $this->conPDO->prepare($query);
            $sentencia->bindParam(1, $estado, PDO::PARAM_INT);
            $sentencia->bindParam(2, $this->id, PDO::PARAM_INT);
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