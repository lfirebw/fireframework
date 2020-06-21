<?php
class Import {
	private static function exportfile($r, $alias = null,$name = null){
	    if(!is_file($r)){
	    	throw new Exception("Archivo no encontrado {$r}");
	    	return;
	    }
	    @require_once($r);
	    if(!is_null($alias) && !is_null($name)){
		    if(!class_exists($alias)){
				class_alias($name,$alias);
			}
	    }
	}
	public static function Model($dir,$alias = null){
		try{
			self::exportfile(MODEL_PATH.$dir.".class.php",$alias,$dir);
		}catch(Exception $e){
			echo $e->getMessage();
			return false;
		}
	}

	public static function Middleware($dir,$alias=null){
		try{

			self::exportfile(APP_PATH."middleware/".$dir.".class.php",$alias,$dir);	    	
		}catch(Exception $e){
			echo $e->getMessage();
			return false;
		}
	}

	public static function Clases($dir,$alias = null){
		try{

			self::exportfile(APP_PATH.$dir.".class.php",$alias,$dir);	    		    	
		}catch(Exception $e){
			echo $e->getMessage();
			return false;
		}
  	}
  	public static function Core($dir,$Strict = true){
  		try{
			$complement = $Strict ? ".class" : '' ;
			self::exportfile(FRAMEWORK_PATH.$dir.$complement.".php");
  		}catch(Exception $e){
  			echo $e->getMessage();
  			return false;
  		}
  	}
  	public static function JScript($filename){
  		try{
  			$extension = end(explode('.', $filename));
  			if(strcmp($extension, 'js') !== 0){
  				throw new Exception("not is JS File extension");
  			}
  			if(!is_file(firecore::getJAVASCRIPT_PATH().$filename)){
  				throw new Exception("Don't exist file");
  			}
  			$stringFile = file_get_contents(firecore::getJAVASCRIPT_PATH().$filename);
  			if(empty($stringFile)){
  				throw new Exception("File string is empty");
  			}
  			echo "<script type='text/javascript'>{$stringFile}</script>";
  		}catch(Exception $e){
  			echo $e->getMessage();
  			return false;
  		}
  	}
}
?>