<?php
class db extends connection{
  protected $conn = false;
  protected $sql;
	
  	/**
	*
	*      * Constructor, to connect to database, select database and set charset
	*
  *      * @para $config string configuration array
	**/
  public function __CONSTRUCT($config = array()){
    $conn = parent::getConnection();
    if($conn){
	     $this->conn = $conn;
       $conn = null;
    }else{
      throw new Exception("Error: No connected to database");
    }
    //$this->conn = mysqli_connect($host,$user,$password) or die('Database connection error');
    //mysqli_select_db($this->conn,$dbname) or die('Database selection error');
    //$this->setchar($charset);
  }
  public function __destruct(){
    parent::disconnect();
  }

  private function setchar($charset){
    $sql = 'set names '.$charset;
    $this->query($sql);
  }
  public function insert($table,$fields,$dates){
    $_fields = "`".implode('`,`',$fields)."`";
    $insertProductsQuery = "INSERT INTO `{$table}` ({$_fields}) VALUES ";
    $count = 0;
    $c = count($fields);
    foreach ( $dates as $key => $value ) {
        $i = 0;
        $tmp_fields = array();
        do{
          // $insertProductsParams[$fields[$i] . $count] =(!is_numeric($value[$fields[$i]])) ? $this->conn->quote($value[$fields[$i]]) : $value[$fields[$i]];
          $insertProductsParams[$fields[$i] . $count] = $value[$fields[$i]];
          $tmp_fields[] = ':'.$fields[$i].$count;
          ++$i;
        }while($i < $c);
        $insertProductsQuery .= "(".implode(',',$tmp_fields)."),";
        ++$count;
    }
    $insertProductsQuery = rtrim($insertProductsQuery,',');
    $preparedStatement = $this->conn->prepare($insertProductsQuery);
    $preparedStatement->execute($insertProductsParams);
  }
  public function update($table,$keyfield,$data){
    
    foreach ( $data as $key => $value ) {
      $uplist = ''; //update fields
      $where = '';
      foreach ($value as $k => $v) {
        if ($k == $keyfield) {
            // If it’s PK, construct where condition
            $where = "`$k`=$v";

        } else {
            // If not PK, construct update list
            $uplist .= "`$k`='$v'".",";
        }
      }
      $uplist = rtrim($uplist,',');
      $sql = "UPDATE `{$table}` SET {$uplist} WHERE {$where}";
      $preparedStatement = $this->conn->prepare($sql);
      $preparedStatement->execute();
    }
  }
  public function iniciarTransaccion(){
    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if($this->conn->getAttribute(PDO::ATTR_AUTOCOMMIT) == 1){
      $this->conn->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
    }
    
    $this->conn->beginTransaction();
  }
  public function cancelarTransaccion(){
    $this->conn->rollback();
    if($this->conn->getAttribute(PDO::ATTR_AUTOCOMMIT) == 0){
      $this->conn->setAttribute(PDO::ATTR_AUTOCOMMIT, 1);
    }
  }
  public function terminarTransaccion(){
    $this->conn->commit();
    if($this->conn->getAttribute(PDO::ATTR_AUTOCOMMIT) == 1){
      $this->conn->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
    }
  }
  public function query($sql){
    $this->sql = $sql;
    //write SQL Statement into log
    //$str = $sql." [".date("Y-m-d H:i:s")."]".PHP_EOL;
    //file_put_contents("log.txt",$str,FILE_APPEND);
    $result = $this->conn->query($this->sql, PDO::FETCH_ASSOC);
    if(!$result){
      throw new Exception($this->errno().':'.$this->error().'<br />Error SQL statement is '.$this->sql.'<br />');
    }
    return $result;
  }
  public function getOne($sql){
    $result = $this->query($sql);
    $row = mysql_fetch_row($result);
    if($row){
      return $row[0];
    }else{
      return false;
    }
  }
  public function getRow($sql){
    if($result = $this->query($sql)){
      $row = mysql_fetch_assoc($result);
      return $row;
    }else{
      return false;
    }
  }
  public function getAll($sql){
    try{
      $list = array();
      $stmt = $this->conn->prepare($sql); 
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      if($stmt->execute()){
        $list = $stmt->fetchAll();
      }
      $stmt = null;
      return $list;
    }catch(Exception $e){
      printf("Error alguno : %s",$e->getMessage());
      throw new Exception("Error Processing Request, getAll on db class", 1);
    }
  }
  
  public function getCol($sql){

      $result = $this->query($sql);

      $list = array();

      while ($row = mysql_fetch_row($result)) {

        $list[] = $row[0];

      }

      return $list;

    }


   

    /**

     * Get last insert id

     */

    public function getInsertId(){

      return $this->conn->lastInsertId();

    }

    /**

     * Get error number

     * @access private

     * @return error number

     */

    public function errno(){

      return mysql_errno($this->conn);

    }

    /**

     * Get error message

     * @access private

     * @return error message

     */

    public function error(){

      return mysql_error($this->conn);

    }
}
?>
