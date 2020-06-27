<?php
declare(strict_types=1);

namespace Fframework\Core;

class Config {
	static protected $dbconfig = array();
	static protected $generalconfig = array();
	static protected $siteconfig = array();

	public static function load() : bool{
		try{
			self::$dbconfig = include(APP_PATH."config".DS."db.php");
			self::$generalconfig = include(APP_PATH."config".DS."general.php");
			self::$siteconfig = include(APP_PATH."config".DS."site.php");
			return true;
		}catch(Exception $e){
			return false;
		}
	}
	public static function DBConfig() : array{
		return self::$dbconfig;
	}
	public static function GeneralConfig() : array{
		return self::$generalconfig;
	}
	public static function SiteConfig() : array{
		return self::$siteconfig;
	}
}

?>
