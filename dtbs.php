<?php

!defined('ATTAINABLE') ? exit("ENEMY") : null;

class DtbsConfigure {
    
    private $host = 'localhost',
            $user = 'root',
            $password = '123456',
            $database = 'bmvc';
    
    protected $connect  = false,
              $connectLink = false;
    
    public function __construct()
    {        
        if ($this->connect === false) {
            $this->connect = true;
            if ($this->connect()) {
                $this->character();
            } else {
                exit;
            }
        } else {
            return $this;
        }        
    }
    
    private function connect()
    {
        if ($this->connectLink = @mysqli_connect($this->host, $this->user, $this->password, $this->database)) {
            return true;
        } else {
            return false;
        }
    }
    
    private function character()
    {
        mysqli_query($this->connectLink, "SET CHARACTER SET 'utf8'");
        mysqli_query($this->connectLink, "SET NAMES 'utf8'");
    }
    
}


class Dtbs extends DtbsConfigure {    
    
    private $queryString = false,
            $queryResult = false,
            $method = false,
            $table = false;
       
    public function __construct()
    {          
        parent::__construct();
    } 
    
    private function __get($table)
    {       
        if ($this->tableIsset($table)->result() === true) {
            $this->table = $table;
            return $this;
        } else {
            exit("Not Exists {$table} table");
        }
    }
    
    private function tableIsset($table)
    { 
        $this->queryString = "SELECT * FROM {$table}";
        return $this;
    }
    
    public function select($field = '*')
    {        
        $this->method = __METHOD__;
        $this->queryString = "SELECT {$field} FROM {$this->table} ";
        return $this;
    }
    
    public function join($beforeField, $afterTable, $afterField)
    {
        $beforeTable = $this->table;
        $this->queryString .= "LEFT JOIN {$afterTable} ON {$afterTable}.{$afterField} = {$beforeTable}.{$beforeField}";
        return $this; 
    }
    
    public function where()
    {
        $where = null;
        $pars = func_get_args();
        $countPars = count($pars);        
        
        if ($countPars  > 0) {
            $where = "WHERE ";
            if ($countPars == 1) {
                $wherePars = (array) $pars[0];
                foreach($wherePars as $key => $value){
                    if (strstr($key, ':')) {
                        $keyExp = explode(':', $key);
                        $keyMe = $keyExp[0];
                        $keyCt = $keyExp[1];
                        $keyCtExp = explode('.', $keyCt);
                        $value = $this->clearString($value);

                        $firstCon = $keyCtExp[0];
                        $lastCon = $keyCtExp[1];
                        $varType = $keyCtExp[2];
                        $oprt = $keyCtExp[3];
                    } else {
                        $where .= "{$key} = {$value} ";
                    }                   
                }
            } else {
                $whereKey = $pars[0];
                $whereValue = $pars[1];
                if (strstr($whereKey, ':')) {
                    $keyExp = explode(':', $whereKey);
                    $keyMe = $keyExp[0];
                    $keyCt = $keyExp[1];
                    $keyCtExp = explode('.', $keyCt);                    

                    $firstCon = $keyCtExp[0] != 'n' ? $keyCtExp[0] == 'a' ? "&&" : "||" : null;
                    $lastCon = $keyCtExp[1] != 'n' ? $keyCtExp[1] == 'a' ? "&&" : "||" : null;;
                    $whereValue = $keyCtExp[2] != 'n' ? $keyCtExp[2] == 'i' ? (int) $whereValue : $whereValue : $whereValue;
                    $value = $this->clearString($whereValue);
                    if ($keyCtExp[3] != 'n') { if ($keyCtExp[3] == 't') { $oprt = "="; } elseif($keyCtExp[3] == 'f') { $oprt = "<>"; } elseif($keyCtExp[3] == 's') { $oprt = "<"; } elseif($keyCtExp[3] == 'b') { $oprt = ">"; } else { $oprt = "="; } } else {$oprt = "=";}
                
                } else {
                    $firstCon = null;
                    $lastCon = null;
                    $varType = null;
                    $oprt = "=";
                }
                $where .= "{$firstCon} {$keyMe} {$oprt} {$value} {$lastCon}";
            }
        } else {
            $where = null;
        }
        $this->queryString .= $where;
        
        echo $this->queryString;     
        
    }
    
    public function like()
    {
        return $this;        
        
    }        
    
    public function order()
    {
        return $this;        
        
    }        
    
    public function limit()
    {
        return $this;        
        
    }        
    
    public function insert()
    {
        $this->method = __METHOD__;
        return $this;
    }
    
    public function update()
    {
        $this->method = __METHOD__;
        return $this;
    }
    
    public function delete()
    {
        $this->method = __METHOD__;
        return $this;
    }
     
    public function lastInsertId()
    {     
        
    }        
    
    private function clearString($string)
    {
        return mysql_real_escape_string(htmlspecialchars(trim($string)));
    }
    
    public function result()
    {
        $results= array();
        $this->queryResult = mysqli_query($this->connectLink, $this->queryString);        
        $aktifMethod = explode('::', $this->method);
        
        switch(end($aktifMethod)){
            
            case 'select':
                if (mysqli_affected_rows($this->connectLink)) {
                    if (mysqli_affected_rows($this->connectLink) > 1) {
                        while($row = mysqli_fetch_assoc($this->queryResult)){
                            array_push($results, $row);
                        }
                        return $results;
                    } else {
                        return mysqli_fetch_assoc($this->queryResult);
                    }
                } else {
                    return false;
                }
                break;
            
            case 'insert':
                
                break;
            
            case 'update':
                
                break;
            
            case 'delete':
                
                break;
            
            default:
                if ($this->queryResult) {
                    return true;
                } else {
                    return false;
                }
                break;            
        }        
    }
        
    public function __destruct()
    {
        if ($this->connect === true) {
            mysqli_close($this->connectLink);
            foreach(get_object_vars($this) as $var => $value){
                $this->{$var} = "false";
            }            
        }
    }
            
}