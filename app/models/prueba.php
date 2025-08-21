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