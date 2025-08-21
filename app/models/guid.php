<?php
session_start();
Class Guid{
    private $guid;
    private $id;
    function __construct(){
        $this->guid="0";
        $this->id="0";
    }
  

    public function getGuid()
    {
        return $this->guid;
    }

  
    public function getId()
    {
        return $this->id;
    }

    public function setGuid($guid)
    {
        $this->guid = $guid;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function  generarGuid($id)
        {
            if (function_exists('com_create_guid') === true)
                return trim(com_create_guid(), '{}');
                
                $data = openssl_random_pseudo_bytes(16);
                $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
                $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
                $this->guid =vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
                $_SESSION[$this->guid] =  $id;
                
        }
      public function dguid($guid){
            $this->id=$_SESSION[$guid];
           
        }
}
$guid=new Guid();
$guid->generarGuid(12);
echo "GUID:".$guid->getGuid();
$guid->dguid($guid->getGuid());
echo "Id:" .$guid->getId();
?>