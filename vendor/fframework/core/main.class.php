<?php
declare(strict_types=1);

use Fframework\Core\Config;
// use Slim\Factory\AppFactory;

require_once(realpath(getcwd()).DIRECTORY_SEPARATOR."vendor".DIRECTORY_SEPARATOR."fframework".DIRECTORY_SEPARATOR."define.php");

include_once(VENDOR_PATH . 'autoload.php');

Config::load();


?>