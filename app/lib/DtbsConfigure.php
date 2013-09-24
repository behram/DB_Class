<?php
/*
 * Bu Class Database işlemlerinde Database connect işlemlerini
 * database seçimi ve database ayarlarını yapıyor *  
 * @author Bilal Say
 * @version 1.0
 */
namespace app\lib;

class DtbsConfigure
{
    /* 
     *  private property
     * @var string $host : DB Host
     * @var string $user : DB User
     * @var string $password : DB Password
     * @var string $database : DB Name
     */    
    private $host = 'localhost', $user = 'root', $password = '123456', $database = 'bmvc';
    
    /*
     *  protected property
     * @var boolen $connectMod : Db connect property
     * @var boolen $connectLink : Db connect link property
     */    
    protected $connectMod  = false, $connectLink = false;
    
    /*
     * Database bağlantı ve Kontrollerini yönetir
     * @return this|void
     */
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
    
    /*
     * Database connect işlemlerini yapar
     * @return boolen
     */
    private function connectInstall()
    {
        if ($this->connectLink = @mysqli_connect($this->host, $this->user, $this->password, $this->database)) {
            return true;
        } else {
            return false;
        }
    }
    
    /*
     * Database karakter setini set eder yükler
     * @return void
     */
    private function dbCharacterTypeSet()
    {
        mysqli_query($this->connectLink, "SET CHARACTER SET 'utf8'");
        mysqli_query($this->connectLink, "SET NAMES 'utf8'");
    }
    
}