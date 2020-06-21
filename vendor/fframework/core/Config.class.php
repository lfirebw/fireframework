<?php
declare(strict_types=1);

class Config {
	public static function load():bool{
		try{
			printf("Hello here load user configuration");
			return true;
		}catch(Exception $e){
			return false;
		}
	}
}

?>
