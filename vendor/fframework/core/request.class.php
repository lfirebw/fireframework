<?php
class request{
	static protected $request;
	
	protected $params;

	public function __CONSTRUCT(){
		//do it
	}

	public static function initialize(){
		try{
			self::$request = new self();

			return self::$request;
		}catch(Exception $e){
			throw new Exception("error in request: ".$e->getMessage());
		}
	}

	public static function get(){
		try{
			return (empty(self::$request)) ? self::initialize() : self::$request;
		}catch(Exception $e){
			throw new Exception("error in request: ".$e->getMessage());
		}
	}
	public static function setParams($arr){
		try{
			if(!is_array($arr)){
				return false;
			}
			self::get()->_setParams($arr);
			return true;
		}catch(Exception $e){
			throw new Exception("error in request: ".$e->getMessage());
		}
	}
	public static function getQuery(){
		$url = $_SERVER['REQUEST_URI'];
		$url = (strstr($url, '?')) ? substr($url, 0,strpos($url,'?')) : $url;
		$_tmpconfig = $GLOBALS['config']['general']['root'];
		if(isset($_tmpconfig) && !empty($_tmpconfig)){
			if(strpos($url, $_tmpconfig) >= 0){
				$url = str_replace($_tmpconfig, '', $url);
			}
		}
		return $url;
	}
	public static function getParams(){
		return self::get()->params;
	}
	public function _setParams($arr){
		$this->params = $arr;
	}
	public function param($key){
		try{
			if(empty($this->params) || is_null($key)){
				return null;
			}
			if(!isset($this->params[$key])){
				if(!is_numeric($key) || !isset($this->params[intval($key)])){
					return null;
				}
			}
			return $this->params[$key];
		}catch(Exception $e){
			throw new Exception("error in request: ".$e->getMessage());
		}
	}
}

?>