<?php
/*
 * Bu Class Database ile ilgili tüm işlemleri yapar
 * INSERT SELECT UPDATE DELETE ... gibi * 
 * @author Bilal Say
 * @version 1.0
 */
namespace app\lib;

use app\lib\DtbsConfigure;

class Dtbs extends DtbsConfigure
{
    /*
     *  private property
     * @var string $queryString : Query string ini tutar
     * @var boolen $querResult  : Query execute sonucunu tutar
     * @var string $method : Query için oan kullanılan method ismini tutar select|insert|update|delete
     * @var string $table : __get sihirbaz methoduna gelen olmayan property ismini O an işlem yapılacak tablo ismi olarak tutar 
     */    
    private $queryString = null, $queryResult = null, $method = null, $table = null;            
    
    /*
     * Extends edilmiş DtbsConfigure classının construct methodunu tetikleyerek Database bağlantı ayarlarını yapar
     * @return void
     */
    public function __construct()
    {          
        parent::__construct();
    } 
    
    /*
     * get sihirbaz methodu Classın içinde olmayan bir değişken çağırıldığında tetikleniyor
     * burada olmayan bu değişken o an işlem yapılacak veritabanı tablosunun ismini tutuyor     * 
     * @params string $table 
     * @return this|exit
     */
    public function __get($table)
    {       
        if ($this->tableIssetQuery($table)->execute() == true) {
            $this->table = $table;
            return $this;
        } else {
            exit("Not Exists {$table} table");
        }
    }
    
    /*
     * @ tableIssetQuery | işlem yapılmak istenen tablonun varlığını kontrol
     * eden query yi oluşturuyor
     * @params string $table
     * @return this
     */
    private function tableIssetQuery($table)
    { 
        $this->queryString = "SELECT * FROM {$table}";
        return $this;
    }
    
    /*
     * select | SELECT query sini oluşturur
     * @params string $field
     * @return this
     */
    public function select($field = '*')
    {   
        $this->method = __METHOD__;
        $this->queryString = "SELECT {$field} FROM {$this->table} ";
        return $this;
    }
    
    /*
     * join | JOIN query sini oluşturu
     * @params string $beforeField
     * @params string $afterTable
     * @params $afterField
     * @return this 
     */
    public function join($beforeField, $afterTable, $afterField)
    {
        $beforeTable = $this->table;
        $this->queryString .= "LEFT JOIN {$afterTable} ON {$afterTable}.{$afterField} = {$beforeTable}.{$beforeField}";
        return $this; 
    }
    
    /*
     * where | WHERE query sini oluşturur
     * @params string $field
     * @params string|integer|... $value
     * @return this
     */
    public function where($field, $value = null)
    {
        if (strstr($this->queryString, 'WHERE') === false) { $where = "WHERE "; } else { $where = ""; }
        
        if (strstr($field, ':')) {
            $fieldExp = explode(':', $field);
            $wField = $fieldExp[0];
            $forkExp = explode('.', $fieldExp[1]);
            if ($forkExp[0] == 'a') { $wNextConnnect = "&&"; } elseif ($forkExp[0] == 'o') { $wNextConnnect = "||"; } else { $wNextConnnect = null; }
            if ($value !== null) { if ($forkExp[1] == 'i') { $wFieldValue = (int) $this->clearString($value); } elseif ($forkExp[1] == 's') { $wFieldValue = (string) "'{$this->clearString($value)}'"; } elseif ($forkExp[1] == 'f') { $wFieldValue = (float) $this->clearString($value); } else { $wFieldValue = (string) "'{$this->clearString($value)}'";} } else { $wFieldValue = null; }
            if ($value !== null) { if ($forkExp[2] == 't') { $wOperator = "="; } elseif($forkExp[2] == 'f') { $wOperator = "<>"; } elseif($forkExp[2] == 's') { $wOperator = "<"; } elseif ($forkExp[2] == 'b') { $wOperator = ">"; } else { $wOperator = "="; } } else { $wOperator = null; }
            if (isset($forkExp[3])) { if ($forkExp[3] == 'o') { $wOpenPrnt = "("; $wClosePrnt = null; } elseif ($forkExp[3] == 'c') { $wOpenPrnt = null; $wClosePrnt = ")"; } elseif ($forkExp[3] == 'oc') {$wOpenPrnt = "("; $wClosePrnt = ")";} else { $wOpenPrnt = null; $wClosePrnt = null; } } else { $wOpenPrnt = null; $wClosePrnt = null; }
            
        } else {
            $wField = $field;
            $wNextConnnect = null;
            $wFieldValue = $value !== null ? (string) "'{$this->clearString($value)}'" : null;
            $wOperator = $value !== null ? "=" : null;
            $wOpenPrnt = null;
            $wClosePrnt = null;
        }
        $wOperator = $wOperator !== null ? " ".$wOperator." " : null;
        $wNextConnnect = $wNextConnnect !== null ? " ".$wNextConnnect." " : null;
        $where .= "{$wOpenPrnt}{$wField}{$wOperator}{$wFieldValue}{$wClosePrnt}{$wNextConnnect}";       
        $this->queryString .= $where;           
        return $this;       
    }
    
    /*
     * like | LIKE query sini oluşturur
     * @params string|integer|... $search
     * @return this
     */
    public function like($search)
    {
        $this->queryString .= " LIKE '%{$this->clearString($search)}%'";
        return $this;        
    }        
    
    /*
     * order | ORDER BY query sini oluşturur
     * @params string $field
     * @params string $orderType
     * @return this
     */
    public function order($field, $orderType = "DESC")
    {
        $this->queryString .= " ORDER BY {$field} {$orderType}";
        return $this;           
    }        
    
    /*
     * limit | LIMIT query sini oluşturur
     * @params integer $first
     * @params integer $last
     * @return this
     */
    public function limit($first, $last = null)
    {
        $last = $last !== null ? ", ".$last : null;
        $this->queryString .= " LIMIT {$first}{$last}";
        return $this;             
    }        
    
    /*
     * insert | INSERT query sini oluşturur
     * @params array $data
     * @return this
     */
    public function insert(array $data)
    {
        $this->method = __METHOD__;
        $insert = "INSERT INTO {$this->table}(";
        foreach (array_keys($data) as $key) {
            if (strstr($key, ':')) {
                $keyExp = explode(':', $key);
                $field = $keyExp[0];
            } else {
                $field = $key;
            }
            
            $insert .= $field.",";
        }
        $insert = rtrim($insert, ',').") VALUES(";
        
        foreach ($data as $key => $value) {            
            if (strstr($key, ':')) {
                $keyExp = explode(':', $key);
                $field = $keyExp[0];
                $forkField = $keyExp[1];
                if ($forkField == 'i') { $fieldValue = (int) $this->clearString($value); } elseif($forkField == 's') { $fieldValue = (string) "'{$this->clearString($value)}'"; } elseif($forkField == 'f') { $fieldValue = (float) $this->clearString($value); } else { $fieldValue = (string) "'{$this->clearString($value)}'"; }
            } else {
                $fieldValue = (string) "'{$this->clearString($value)}'";
            }
            
            $insert .= "{$fieldValue},"; 
        }
        $insert = rtrim($insert, ',').")";
        $this->queryString = $insert;
        return $this;
    }
    
    /*
     * update | UPDATE query sini oluşturur
     * @params array $data
     * @return this
     */
    public function update(array $data)
    {
        $this->method = __METHOD__;
        $update = "UPDATE {$this->table} SET ";
        foreach ($data as $key => $value) {            
            if (strstr($key, ':')) {
                $keyExp = explode(':', $key);
                $field = $keyExp[0];
                $forkField = $keyExp[1];
                if ($forkField == 'i') { $fieldValue = (int) $this->clearString($value); } elseif($forkField == 's') { $fieldValue = (string) "'{$this->clearString($value)}'"; } elseif($forkField == 'f') { $fieldValue = (float) $this->clearString($value); } else { $fieldValue = (string) "'{$this->clearString($value)}'"; }
            } else {
                $field = $key;
                $fieldValue = (string) "'{$this->clearString($value)}'";
            }
            
            $update .= "{$field} = {$fieldValue}, "; 
        }
        $update = rtrim($update, ', ');
        $this->queryString = $update." ";        
        return $this;
    }
    
    /*
     * delete | DELETE query sini oluşturur
     * @return this
     */
    public function delete()
    {
        $this->method = __METHOD__;
        $update = "DELETE FROM {$this->table} ";
        $this->queryString = $update;
        return $this;
    }
     
    /*
     * lastInsertId | Veritabanına eklenmiş son kaydın id sini verir
     * @return mysqli_insert_id
     */
    public function lastInsertId()
    {     
        return mysqli_insert_id($this->connectLink);
    }        
    
    /*
     * clearString | Veritabnında kullanacağımız valueleri güvenlik için temizlemek
     * @return mysql_real_escape_string|htmlspecialchars|trim 
     */
    private function clearString($string)
    {
        return mysql_real_escape_string(htmlspecialchars(trim($string)));
    }
    
    /*
     * execute | Oluşturulmuş query leri çalıştırır ve sonucları return olarak dönderir
     * @return boolen|array
     */
    public function execute()
    {
        $results= array();
        $this->queryString = trim($this->queryString);
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
                if ($this->queryResult) {
                    return true;
                } else {
                    return false;
                }
                break;
            
            case 'update':
                if ($this->queryResult) {
                    return true;
                } else {
                    return false;
                }
                break;
            
            case 'delete':
                if ($this->queryResult) {
                    return true;
                } else {
                    return false;
                }
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
    
    /*
     * Query ve Database connect ile ilgili property leri resetler
     * @return void
     */
    public function __destruct()
    {
        if ($this->connectMod === true) {
            mysqli_close($this->connectLink);
            foreach(get_object_vars($this) as $var => $value){
                $this->{$var} = null;
            }            
        }
    }
            
}