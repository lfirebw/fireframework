<?php
date_default_timezone_set('America/Lima');

require_once(HELPER_PATH."dompdf2".DS."lib".DS."html5lib".DS."Parser.php");
require_once(HELPER_PATH."dompdf2".DS."lib".DS."php-font-lib".DS."src".DS."FontLib".DS."Autoloader.php");
require_once(HELPER_PATH."dompdf2".DS."lib".DS."php-svg-lib".DS."src".DS."autoload.php");
require_once(HELPER_PATH."dompdf2".DS."src".DS."Autoloader.php");
//require_once(HELPER_PATH."dompdf2".DS."src".DS."Dompdf.php");

Dompdf\Autoloader::register();

use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * Dompdf Library for generate pdf in system
 */
class pdf{
	protected $_Dompdf;
	public function __construct()
	{
		$options = new Options();
		$options->set('enable_html5_parser', true);
		$options->set('chroot', 'path-to-test-html-files');
		$this->_Dompdf = new Dompdf($options);
		// $this->_Dompdf = new Dompdf();
	}
	public function getDompdf(){
		return $this->_Dompdf;
	}
}

?>