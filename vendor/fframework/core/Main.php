<?php
declare(strict_types=1);

namespace Fframework\Core;

use Fframework\Core\Config;
use Fframework\Core\Database;
use Fframework\Core\Route;
use Fframework\Core\ContainerFactory;
use Selective\BasePath\BasePathMiddleware;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

date_default_timezone_set('America/Lima');
require_once("define.php");
include_once(VENDOR_PATH . 'autoload.php');

class Main
{
	static protected $CONTROLLER_PATH;
	static protected $VIEW_PATH;
	static protected $CURR_CONTROLLER_PATH;
	static protected $CURR_VIEW_PATH;
	static protected $JAVASCRIPT_PATH;
	static protected $CSS_PATH;
	static protected $LAYOUT_PATH;

	public static function load()
	{
		//load all config
		Config::load();
		
		//load database
		new Database(Config::DBConfig());

		self::$CONTROLLER_PATH = APP_PATH . "controllers" .DS;
	    self::$VIEW_PATH = APP_PATH . "views". DS;
	    self::$JAVASCRIPT_PATH = APP_PATH. "javascripts".DS;
	    self::$CSS_PATH = APP_PATH. "styles".DS;
	    self::$LAYOUT_PATH = self::$VIEW_PATH. "layout" . DS;
		// Create the container for dependency injection.
		try {
		    $container = ContainerFactory::create(APP_PATH);
		} catch (Exception $e) {
		    die($e->getMessage());
		}
		// var_dump($container);exit();
		// Set the container to create the App with AppFactory.
		
		AppFactory::setContainer($container);
		$_app = AppFactory::create();
		
		if(isset(Config::GeneralConfig()['cache']) && Config::GeneralConfig()['cache'] !== false){
			$_app->getRouteCollector()->setCacheFile(
			    ROOT . 'cache/routes.cache'
			);
		}
		
		// Add the twig middleware.
		$_app->addMiddleware(
			TwigMiddleware::create($_app, $container->get(Twig::class))
		);
		// Add the routing middleware.
		$_app->addRoutingMiddleware();
		
		$_app->setBasePath(Config::GeneralConfig()['root']);

		$_app->add(new BasePathMiddleware($_app));

		// Add error handling middleware.
		$displayErrorDetails = true;
		$logErrors = true;
		$logErrorDetails = false;
		$_app->addErrorMiddleware($displayErrorDetails, $logErrors, $logErrorDetails);
		
		// var_dump($_app);
		$http = !empty($_SERVER['HTTPS']) ? 'https://' : 'http://' ;
		$_http_host = rtrim($_SERVER['HTTP_HOST'],'/');
		$rootconfig = Config::GeneralConfig()['root'];
		$base_url = "{$http}{$_http_host}{$rootconfig}";
		//set global variables
		$container->get(Twig::class)->getEnvironment()->addGlobal('session', $_SESSION);
		$container->get(Twig::class)->getEnvironment()->addGlobal('rootPath', APP_PATH);
		$container->get(Twig::class)->getEnvironment()->addGlobal('assetsPath', $base_url.'/public/assets/');
		$container->get(Twig::class)->getEnvironment()->addGlobal('site_config', Config::SiteConfig());
		// $container->get(Twig::class)->getEnvironment()->addGlobal('globalAssetsPath', $cms_settings['base_url'] . "assets/general/");
		// $container->get(Twig::class)->getEnvironment()->addGlobal('lang', $lang_array);
		Route::setApp($_app);
	}
	public static function run(){
		//load routes
		
		Route::importRouting(APP_PATH."route.php");
		// Run the app.
		Route::App()->run();
	}
}

Main::load();
Main::run();
?>