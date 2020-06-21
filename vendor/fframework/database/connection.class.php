<?php
class connection{
	public static $_conn = null;
	public static function connect(){
		try{
			$_con = self::$_conn;
			if(is_null($_con)){

				$options = array(
		      PDO::ATTR_CASE => PDO::CASE_NATURAL,
		      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		      PDO::ATTR_ORACLE_NULLS => PDO::NULL_EMPTY_STRING
		    );

		    $host = isset($GLOBALS['config']['db']['host']) ? $GLOBALS['config']['db']['host'] : 'localhost';
		    $user = isset($GLOBALS['config']['db']['user']) ? $GLOBALS['config']['db']['user'] : 'root';
		    $password = isset($GLOBALS['config']['db']['password']) ? $GLOBALS['config']['db']['password'] : '';
		    $dbname = isset($GLOBALS['config']['db']['dbname']) ? $GLOBALS['config']['db']['dbname'] : '';
		    $port = isset($GLOBALS['config']['db']['port']) ? $GLOBALS['config']['db']['port'] : '3306';
		    $charset = isset($GLOBALS['config']['db']['charset']) ? $GLOBALS['config']['db']['charset'] : 'utf-8';
		    $conn = new PDO("mysql:host=$host;dbname=$dbname",$user,$password,$options);
		    if($conn){
			     self::$_conn = $conn;
		       unset($conn);
		    }else{
		      throw new Exception("Error: No connected to database");
		    }
			}/*end if check*/
			return self::$_conn;
		}catch(Exception $e){
			printf("error on connection: %s", $e->getMessage());
			return null;
		}
	}
	public static function getConnection(){
		return (!is_null(self::$_conn)) ? self::$_conn : self::connect();
	}
	public static function disconnect(){
		try{
			self::$_conn = null;
		}catch(Exception $e){
			return null;
		}
	}
}
?>