<?php

include_once "conexion.php";
class Usuario 
{

    private $usuario;

    private $nombre;

    private $apellido;

    private $email;

    private $doc;
    private $usu;
    private $destino;

    private $jerarquia;

    private $categoria;

    private $rol;

    private $destinoModel;
    private $conPDO ;

    /**
     *
     * @param mixed $rol
     */
    public function setRol($rol)
    {
        $this->rol = $rol;
    }

    public function getRol()
    {
        return $this->rol;
    }

    /**
     *
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     *
     * @return mixed
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     *
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     *
     * @return mixed
     */
    public function getDoc()
    {
        return $this->doc;
    }

    /**
     *
     * @return mixed
     */
    public function getDestino()
    {
        return $this->destino;
    }

    /**
     *
     * @return mixed
     */
    public function getJerarquia()
    {
        return $this->jerarquia;
    }

    /**
     *
     * @return mixed
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     *
     * @param mixed $usuario
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     *
     * @param mixed $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     *
     * @param mixed $apellido
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    }

    /**
     *
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     *
     * @param mixed $doc
     */
    public function setDoc($doc)
    {
        $this->doc = $doc;
    }

    /**
     *
     * @param mixed $destino
     */
    public function setDestino($destino)
    {
        $this->destino = $destino;
    }

    /**
     *
     * @param mixed $jerarquia
     */
    public function setJerarquia($jerarquia)
    {
        $this->jerarquia = $jerarquia;
    }

    /**
     *
     * @param mixed $categoria
     */
    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;
    }
    public function setUsu($usu)
    {
        $this->usu = $usu;
    }

  

    public function __construct()
    {
       
        $this->setUsuario('');
        $this->setDoc('');
        $this->setDestino('');
        $this->setJerarquia('');
        $this->setEmail('');
        $this->setRol('');
        $this->usu='';
        $this->conPDO = conectar();
        //$_SESSION['estado']='';
        //$this->destinoModel = new Destino();
    }

    public function setSesionUsuario() 
    {
        $_SESSION['USUARIO'] = $this->toArray();
    }

    public function toArray()
    {
        $arr = array();
        $arr['usuario'] = $this->getUsuario();
        $arr['nombre'] = $this->getNombre();
        $arr['apellido'] = $this->getApellido();
        $arr['email'] = $this->getEmail();
        $arr['doc'] = $this->getDoc();
        $arr['destino'] = $this->getDestino();
        $arr['jerarquia'] = $this->getJerarquia();
        $arr['categoria'] = $this->getCategoria();
        $arr['rol'] = $this->getRol();

        return $arr;
    }

    
    public function getPrefectoWebService($oam_user)
    {
        //$oam_user = $_SERVER['HTTP_OAM_REMOTE_USER'];
       
        $url = "http://osb.prefecturanaval.gov.ar/Gebipa/Servicios/Prefecto?wsdl";
        $parametros = array(
            "ndoc" => $oam_user
        );
        /*
         * Datos del personal conectado mediante SOAPClient
         */
        $clientSoap = new \SoapClient($url);
        $result = $clientSoap->prefecto($parametros);

        if (isset($result->prefecto)) {
            $prefecto = $result->prefecto;

            $this->setApellido($prefecto->apellido);
            $this->setNombre($prefecto->nombres);
            $this->setDoc($prefecto->nroDocumento);
            $this->setJerarquia($prefecto->grado . $prefecto->cuerpo . $prefecto->escalafon);
            $this->setDestino('IFOR');//$prefecto->depCuatrigrama
            $this->setEmail($prefecto->email);
           // echo $prefecto->depCuatrigrama;
            $usuario = $this->getJerarquia() . ' ' . $prefecto->nombres . ' ' . $prefecto->apellido;
            $this->setUsuario($usuario);
            
            $dato=array("DNI"=>$oam_user);
            $_SESSION['USUARIO'] = $this->toArray();
           //echo "prefecto";
            return true;
            
        } else {
            $_SESSION['USUARIO'] = NULL;
           
            return false;
        }
    }

    public function getRolesWebService($oam_user)
    {
        //$log  = $this->getLogger();

        try {
            //$oam_user = $_SERVER['HTTP_OAM_REMOTE_USER'];
            $url = "http://osb.prefecturanaval.gov.ar:80/proxy/SBUsuariosPna/ConsultaUsuPna?wsdl";
            $clientSoap = new \SoapClient($url);
            $parametros = [
                //  cambiar dni por oam '22042548'
                'ndoc' => $oam_user
            ];
            $result = $clientSoap->execute($parametros);
          // print_r($result);
            if (is_array($result->usuario)) {
                $findme = 'DEDU_003';
                foreach ($result->usuario as $rol) {
                    //print_r($rol);
                    if (strcmp($rol->nroSistema, $findme) == 0) {
                     //   echo "entro";
                        if($rol->nroPrivilegioSistema==1){
                            $_SESSION['nivel']=1;
                        }
                        if($rol->nroPrivilegioSistema==6){
                            $_SESSION['nivel']=4;
                        }
                        if($rol->nroPrivilegioSistema==8){
                            $_SESSION['nivel']=8;
                        }
                        if($rol->nroPrivilegioSistema==9){
                            $_SESSION['nivel']=8;

                            $_SESSION['nivelUsu']=9;
                        }
                        
                        return true;
                    }
                }
                
            } else {
                
                return false;
            }
        } catch (Throwable $e) {
            //echo 'Error WebService busqueda de Rol.';
            //include '../errors/error_msj.php';
            return false;
        }
    }
   
    
    public function autenticar($oamUser) {
        try {
            //SESSION SIAC_USUARIO == NULL
            $usu=$_POST['dni'];
            if($usu!=''){
               // $oamUser=$usu;
            }
            else{
               // $oamUser = $_SERVER['HTTP_OAM_REMOTE_USER'];
            }
            
           // echo "oam".$oamUser;    
            if(!empty($oamUser) && is_numeric($oamUser) && empty($_SESSION['USUARIO'])) 
            {
                //echo "P".$this->getPrefectoWebService($oamUser)."R".$this->getRolesWebService($oamUser);
                if($this->getPrefectoWebService($oamUser) and $this->getRolesWebService($oamUser)){
                    $_SESSION['USUARIO'] = $this->toArray();
                    $_SESSION['estado']=0;
                
                    return 0;
                }
                else{
                    return 1;
                    $_SESSION = NULL;
                }
               
            }
            //SESSION SIAC_USUARIO != NULL
            else if(!empty($oamUser) && is_numeric($oamUser) && !empty($_SESSION['USUARIO'])) 
            {   
               // echo "P".$this->getPrefectoWebService($oamUser)."R".$this->getRolesWebService($oamUser);
                if($this->getPrefectoWebService($oamUser) and $this->getRolesWebService($oamUser)){
                    $_SESSION['USUARIO'] = $this->toArray();
                    $_SESSION['estado']=0;
                    return 0;
                }
                else{
                    return 1;
                    $_SESSION = NULL;
                }
            } 
            else 
            {
                $_SESSION = NULL;
               //$this->logger->addError('ERROR FATAL: No se pudo autenticar usuario en sesion de servidor.');
                return 1;
            }
        } catch (\Exception $e) {
            return 2;

        }
    }

   
    public function consultarSession() 
    {   
        
        $inactividad = 6000;
        // Comprobar si $_SESSION["timeout"] está establecida
        if(isset($_SESSION["timeout"])) {
            // Calcular el tiempo de vida de la sesión (TTL = Time To Live)
            $sessionTTL = time() - $_SESSION["timeout"];
            if($sessionTTL > $inactividad) {
                $_SESSION = NULL;
                session_destroy();
                header("Location: https://www.prefecturanaval.gob.ar");
            }
            else
            {
                if($this->autenticar()) {
                    session_regenerate_id();    
                }
            }
        }
        // El siguiente key se crea cuando se inicia sesión
        $_SESSION["timeout"] = time();
        //devolver 
    }

    public function logout()
    {
        unset($_SESSION['USUARIO']);
        session_unset();
        session_destroy();
        header("Location: https://www.prefecturanaval.gob.ar");
    }
    
}

