<?php

namespace app\lib;

class DtbsConfigure {
    
    private $host = 'localhost',
            $user = 'root',
            $password = '123456',
            $database = 'bmvc';
    
    protected $connectMod  = false,
              $connectLink = false;
    
    public function __construct()
    {        
        if ($this->connectMod === false) {
            $this->connectMod = true;
            if ($this->connectInstall()) {
                $this->dbCharacterTypeSet();
            } else {
                exit;
            }
        } else {
            return $this;
        }        
    }
    
    private function connectInstall()
    {
        if ($this->connectLink = @mysqli_connect($this->host, $this->user, $this->password, $this->database)) {
            return true;
        } else {
            return false;
        }
    }
    
    private function dbCharacterTypeSet()
    {
        mysqli_query($this->connectLink, "SET CHARACTER SET 'utf8'");
        mysqli_query($this->connectLink, "SET NAMES 'utf8'");
    }
    
}