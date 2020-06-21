<?php

// framework/core/Model.class.php

// Base Model Class

class model{

  protected $db; //database connection object

  protected $table; //table name

  protected $fields = array();  //fields list

  public function __construct($table){
    try{
      $dbconfig['host'] = $GLOBALS['config']['db']['host'];

      $dbconfig['user'] = $GLOBALS['config']['db']['user'];

      $dbconfig['password'] = $GLOBALS['config']['db']['password'];

      $dbconfig['dbname'] = $GLOBALS['config']['db']['dbname'];

      $dbconfig['port'] = $GLOBALS['config']['db']['port'];

      $dbconfig['charset'] = $GLOBALS['config']['db']['charset'];
      
      $this->db = new db($dbconfig);
      $this->table = $GLOBALS['config']['db']['prefix'] . $table;
      $this->getFields();
    }catch(Exception $e){
      printf("MODEL ERROR __ %s",$e->getMessage());
      exit(0);
    }
  }

    /**

     * Get the list of table fields

     *

     */

    private function getFields(){

        $sql = "DESC ". $this->table;
        $result = $this->db->getAll($sql);
        foreach ($result as $v) {

            $this->fields[] = $v['Field'];

            if ($v['Key'] == 'PRI') {

                // If there is PK, save it in $pk

                $pk = $v['Field'];

            }

        }

        // If there is PK, add it into fields list

        if (isset($pk)) {

            $this->fields['pk'] = $pk;

        }

    }
    public function iniciarTransaccion(){
      $this->db->iniciarTransaccion();
    }
    public function terminarTransaccion(){
      $this->db->terminarTransaccion();
    }
    public function cancelarTransaccion(){
      $this->db->cancelarTransaccion();
    }
    public function insertvalues($data){
      //:field
      //data required this format array(array('field'=>'value'))
      $_fields = array();
      foreach(end($data) as $k => $v){
        if (in_array($k, $this->fields)) {
          $_fields[] = "{$k}";
        }
      }
      $this->db->insert($this->table,$_fields,$data);
    }
    public function updatevalues($data){
      $this->db->update($this->table,$this->fields['pk'],$data);
    }
    /**

     * Insert records

     * @access public

     * @param $list array associative array

     * @return mixed If succeed return inserted record id, else return false

     */

    public function insert($list){
        $field_list = '';  //field list string

        $value_list = '';  //value list string

        foreach ($list as $k => $v) {

            if (in_array($k, $this->fields)) {

                $field_list .= "`".$k."`" . ',';
                if(is_string($v)){
                  $value_list .= "'".str_replace(array("'",chr(145),chr(146)),array("'","'","'"),$v)."'" . ',';
                }else{
                  $value_list .= "'".$v."'". ',';
                }

            }

        }

        // Trim the comma on the right

        $field_list = rtrim($field_list,',');

        $value_list = rtrim($value_list,',');

        // Construct sql statement

        $sql = "INSERT INTO `{$this->table}` ({$field_list}) VALUES ($value_list)";
        

        if ($this->db->query($sql)) {

            // Insert succeed, return the last record’s id
            
            return $this->db->getInsertId();

            //return true;

        } else {
            // Insert fail, return false

            return false;

        }

       

    }

    /**

     * Update records

     * @access public

     * @param $list array associative array needs to be updated

     * @return mixed If succeed return the count of affected rows, else return false

     */

    public function update($list,$filter = null){

        $uplist = ''; //update fields

        $where = 0;   //update condition, default is 0

        foreach ($list as $k => $v) {

            if (in_array($k, $this->fields)) {

                if ($k == $this->fields['pk']) {

                    // If it’s PK, construct where condition

                    $where = "`$k`=$v";

                } else {

                    // If not PK, construct update list
                	$v = str_replace(array("'",chr(145),chr(146)),array("'","'","'"),$v);
                    $uplist .= "`$k`='$v'".",";

                }

            }

        }

        if(!empty($filter)){
          $wheres = array();
          foreach($filter as $k => $v){
            $condition = "=";
            $posSign = $this->getPosSign($k);
            if($posSign !== false){
              $condition = $this->setcondition($k,$posSign);
              $k = $this->clearcondition($k);
            }
            $wheres[]="`$k` $condition $v";
          }
          if(!empty($where)){
            $wheres[] = $where;
          }
          $where = implode(' AND ',$wheres);
        }

        // Trim comma on the right of update list

        $uplist = rtrim($uplist,',');

        // Construct SQL statement

        $sql = "UPDATE `{$this->table}` SET {$uplist} WHERE {$where}";

       

        if ($this->db->query($sql)) {

            // If succeed, return the count of affected rows

            //if ($rows = mysql_affected_rows()) {

                // Has count of affected rows  

                //return $rows;

            //} else {

                // No count of affected rows, hence no update operation

                //return false;
                return true;
            //}    

        } else {

            // If fail, return false

            return false;

        }

       

    }
    public function setField($id, $field, $value){
      $sql = "UPDATE `{$this->table}` SET {$field} = {$value} WHERE {$this->fields['pk']} = {$id}";
      if ($this->db->query($sql)) {
        return true;
      } else {
        return false;
      } 
    }

    /**

     * Delete records

     * @access public

     * @param $pk mixed could be an int or an array

     * @return mixed If succeed, return the count of deleted records, if fail, return false

     */

    public function delete($pk){

        $where = 0; //condition string

        //Check if $pk is a single value or array, and construct where condition accordingly

        if (is_array($pk)) {

            // array

            $where = "`{$this->fields['pk']}` in (".implode(',', $pk).")";

        } else {

            // single value

            $where = "`{$this->fields['pk']}`=$pk";

        }

        // Construct SQL statement

        $sql = "DELETE FROM `{$this->table}` WHERE $where";

        if ($this->db->query($sql)) {

            // If succeed, return the count of affected rows
            /*
            if ($rows = mysql_affected_rows()) {

                // Has count of affected rows

                return $rows;

            } else {

                // No count of affected rows, hence no delete operation

                return false;

            } 
            */       
            return true;
        } else {

            // If fail, return false

            return false;

        }

    }
    public function setLimite($inicio,$fin){
      $this->limit = " LIMIT {$inicio},{$fin}";
    }
    public function writeLimit(&$sql){
      if(isset($this->limit) && !empty($this->limit)){
        $sql .= $this->limit;
        unset($this->limit); 
      }
    }
    /**

     * Get info based on PK

     * @param $pk int Primary Key

     * @return array an array of single record

     */

    public function selectByPk($pk){

        $sql = "select * from `{$this->table}` where `{$this->fields['pk']}`=$pk";

        return $this->db->getAll($sql);

    }
    /*
      aplicacion alternativa de consultas
    */
    public function selectRel($inners,$campos = '*',$filter = null,$options = null){
      $parent = 97;
      $parentletterString = chr($parent);
      $sql = "SELECT {$campos} FROM `{$this->table}` {$parentletterString}";
      if(empty($inners)){
        return $this->select($filter,$options);        
      }
      $son = $parent + 1;
      $son_dictionary = array();

      foreach ($inners as $key => $value) {
        $sonletter = chr($son);
        $parentletter = chr($parent);

        $tipyJoin = 'INNER JOIN';
        $keytipjoin = 'innerjoin';

        if(array_key_exists('innerjoin', $value)){
          $tipyJoin = 'INNER JOIN';
          $keytipjoin = 'innerjoin';
        }else if(array_key_exists('leftjoin', $value)){
          $tipyJoin = 'LEFT JOIN';
          $keytipjoin = 'leftjoin';
        }else if(array_key_exists('rightjoin', $value)){
          $tipyJoin = 'RIGHT JOIN';
          $keytipjoin = 'rightjoin';
        }else{
          $tipyJoin = 'JOIN';
          $keytipjoin = 'join';
        }
        $son_dictionary[$value[$keytipjoin]] = $sonletter;
        if(!empty($value['table'])){
          $tmp_son_dictionary = array_flip($son_dictionary);
          $_index = array_search($value['table'], $tmp_son_dictionary);
          if($_index !== false){
              $parentletter = $_index;
          }
        }
        $campo1 = "{$sonletter}.{$value['on'][0]}";
        $campo2 = "{$parentletter}.{$value['on'][1]}";
        $sql .= " {$tipyJoin} ".$value[$keytipjoin]." ".$sonletter." ON ".$campo1." = ".$campo2;
        ++$son;
      }//end foreach

      if(!empty($filter)){
        $cond = array();
        foreach($filter as $key => $value){
          $cond[] = "{$parentletterString}.{$key} = '{$value}'";
        }
        $sql .= ' WHERE '.implode(' AND ', $cond);
      }

      if(!is_null($options)){
        if(isset($options['orderBy'])){
          if(is_array($options['orderBy'])){
            $sql .= ' ORDER BY '.$options['orderBy'][0].' '.strtoupper($options['orderBy'][0]);
          }else if(is_string($options['orderBy'])){
            $sql .= ' ORDER BY '.$options['orderBy'];
          }
        }
      }
      $this->writeLimit($sql);

      return $this->db->getAll($sql);
    }
    private function getPosSign($string){
      try{
        $arr = array('>','<','%');
        $c = count($arr); $i = 0;

        do{
            $_strpos = strpos($string, $arr[$i]);
            if($_strpos !== false){
                return $_strpos;
            }
            ++$i;
        }while ($i < $c);
        return false;
      }catch(Exception $e){
        return null;
      }
    }
    private function setcondition($string,$pos){
      try{
        $result = false;
        if(strpos($string, '%') !== false){
          $result = 'LIKE';
        }else{
          $result = substr($string, $pos);
        }
        return $result;
      }catch(Exception $e){
        return null;
      }
    }
    private function clearcondition($string){
      try{ 
        $arr = array('>=','<=','>','<','%'); 
        $arr_reem = array_fill(0,count($arr),''); 
        return str_replace($arr, $arr_reem, $string);
      }catch(Exception $e){
        return null;
      }
    }
    public function select($filter = null,$options = null){
      $sql = "SELECT * FROM `{$this->table}`";
      if(!empty($filter)){
        $cond = array();
        foreach($filter as $key => $value){
          $posSign = $this->getPosSign($key);
          if($posSign !== false){
            $condition = $this->setcondition($key,$posSign);
            $key = $this->clearcondition($key);
            if(strcasecmp('LIKE', $condition) === 0){
              $cond[] = "{$key} {$condition} '%{$value}%'";
            }else{
              $cond[] = "{$key} {$condition} '{$value}'";
            }
          }else{
            $cond[] = "{$key} = '{$value}'";
          }

        }
        $sql .= ' WHERE '.implode(' AND ', $cond);
      }
      if(!empty($options)){
        if(isset($options['orderBy'])){
          if(is_array($options['orderBy'])){
            $sql .= ' ORDER BY '.$options['orderBy'][0].' '.strtoupper($options['orderBy'][0]);
          }else if(is_string($options['orderBy'])){
            $sql .= ' ORDER BY '.$options['orderBy'];
          }
        }
      }
      $this->writeLimit($sql);
      return $this->db->getAll($sql);
    }

    public function selectByRange($range = null,$filter = null){
      $sql = "SELECT * FROM `{$this->table}`";
      $keys = (!empty($range)) ? array_keys($range) : null;
      if(empty($range) || $keys == null || count($range[$keys[0]]) < 2){
        return false;
      }
      if(!empty($range)){
        $wheres = array();
        $values = ( strtotime($range[$keys[0]][0])) ? "#{$range[$keys[0]][0]}# AND #{$range[$keys[0]][1]}#" : "{$range[$keys[0]][0]} AND {$range[$keys[0]][1]}";
        $wheres[] = "{$keys[0]} BETWEEN {$values}";
        if(!empty($filter)){
          foreach($filter as $key => $value){
            $wheres[] = "{$key} = '{$value}'";
          }
        }
        $sql .= " WHERE ".implode(' AND ', $wheres);
      }

      $this->writeLimit($sql);

      return $this->db->getAll($sql);
    }

    public function lastindex(){
      $sql = "select MAX({$this->fields['pk']}) AS id from {$this->table}";
      $_tmp = $this->db->getAll($sql);   

      return intval($_tmp[0]['id']);
    }

    /**

     * Get the count of all records

     *

     */

    public function total($filter = null){

        $sql = "select count(*) AS total from {$this->table}";
        if(!is_null($filter)){
          $cond = array();
          foreach($filter as $key => $value){
            $cond[] = "{$key} = '{$value}'";
          }
          $sql .= ' WHERE '.implode(' AND ', $cond);
        }
        $this->setLimite(0,1);
        $this->writeLimit($sql);
        $_tmp = $this->db->getAll($sql);   

        return intval($_tmp[0]['total']);
    }

    /**

     * Get info of pagination

     * @param $offset int offset value

     * @param $limit int number of records of each fetch

     * @param $where string where condition,default is empty

     */

    public function pageRows($offset, $limit,$where = ''){

        if (empty($where)){

            $sql = "select * from {$this->table} limit $offset, $limit";

        } else {

            $sql = "select * from {$this->table}  where $where limit $offset, $limit";

        }

       

        return $this->db->getAll($sql);

    }

}
?>