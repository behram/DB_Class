<?php

namespace app\lib;

use app\lib\DtbsConfigure;

class Dtbs extends DtbsConfigure {    
    
    private $queryString = false,
            $queryResult = false,
            $method = false,
            $table = false;
    
    public function __construct()
    {          
        parent::__construct();
    } 
    
    public function __get($table)
    {       
        if ($this->tableIssetQuery($table)->result() === true) {
            $this->table = $table;
            return $this;
        } else {
            exit("Not Exists {$table} table");
        }
    }
    
    private function tableIssetQuery($table)
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
        $where = "WHERE ";
        $pars = func_get_args();
        $countPars = count($pars);        
        
        if ($countPars  > 0) {
            if ($countPars == 1) {
                if (is_array($pars[0])) {
                    foreach($pars[0] as $field => $value){
                        if (strstr($field, ':')) {
                            $fieldExp = explode(':', $field);
                            $wField = $fieldExp[0];
                            $fork = $fieldExp[1];
                            $forkExp = explode('.', $fork);
                            $wFieldValue = $this->clearString($value);

                            $wNextConnnect = $forkExp[0] != 'n' ? $forkExp[0] == 'a' ? "&&" : "||" : null;
                            $fieldValueType = $forkExp[1];
                            $value = $fieldValueType != 'n' ? $fieldValueType == 'i' ? (int) $value : "'$value'" : "'$value'";
                            $wFieldValue = $this->clearString($value);
                            if ($forkExp[2] != 'n') { if ($forkExp[2] == 't') { $wOperator = "="; } elseif($forkExp[2] == 'f') { $wOperator = "<>"; } elseif($forkExp[2] == 's') { $wOperator = "<"; } elseif($forkExp[2] == 'b') { $wOperator = ">"; } else { $wOperator = "="; } } else { $wOperator = "=";}
                        } else {
                            $wField = $field;
                            $wFieldValue = $value;
                            $wNextConnnect = null;
                            $wOperator = "=";
                        }   
                        
                        $where .= "{$wField} {$wOperator} {$wFieldValue} {$wNextConnnect} ";
                    }
                } else {
                    $where .= $pars[0];
                }
                
            } else {
                $field = $pars[0];
                $value = $pars[1];
                if (strstr($field, ':')) {
                    $fieldExp = explode(':', $field);
                    $wField = $fieldExp[0];
                    $fork = $fieldExp[1];
                    $forkExp = explode('.', $fork);                    

                    $wNextConnnect = $forkExp[0] != 'n' ? $forkExp[0] == 'a' ? "&&" : "||" : null;
                    $fieldValueType = $forkExp[1];
                    $value = $fieldValueType != 'n' ? $fieldValueType == 'i' ? (int) $value : "'$value'" : "'$value'";
                    $wFieldValue = $this->clearString($value);
                    if ($forkExp[2] != 'n') { if ($forkExp[2] == 't') { $wOperator = "="; } elseif($forkExp[2] == 'f') { $wOperator = "<>"; } elseif($forkExp[2] == 's') { $wOperator = "<"; } elseif($forkExp[2] == 'b') { $wOperator = ">"; } else { $wOperator = "="; } } else { $wOperator = "=";}
                
                } else {
                    $wNextConnnect = "&&";
                    $wField = $field;
                    $wFieldValue = $this->clearString($value);
                    $wOperator = "=";
                }
                $where .= "{$wField} {$wOperator} {$wFieldValue} {$wNextConnnect} ";
            }
        } else {
            $where = null;
        }
        $this->queryString .= $where;
        
        //echo $this->queryString;     
        return $this;
        
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
        if ($this->connectMod === true) {
            mysqli_close($this->connectLink);
            foreach(get_object_vars($this) as $var => $value){
                $this->{$var} = "false";
            }            
        }
    }
            
}