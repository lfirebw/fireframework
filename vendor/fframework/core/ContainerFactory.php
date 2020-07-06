<?php
declare(strict_types=1);

namespace Fframework\Core;

use DI\ContainerBuilder;
use Exception;
use Psr\Container\ContainerInterface;

/**
 * 
 */
class ContainerFactory
{
	protected $containerBuilder;

	/**
     * @param string $rootPath
     *
     * @return ContainerInterface
     * @throws Exception
     */
	public static function create(string $rootPath):ContainerInterface{
        $separator = DS;
        $core = CORE_PATH;
        $containerBuilder = new ContainerBuilder();

        $containerBuilder->addDefinitions("{$core}views-definitions.php");
		$containerBuilder->addDefinitions("{$rootPath}controllers{$separator}config.php");
        $containerBuilder->addDefinitions("{$rootPath}library{$separator}config.php");

        return $containerBuilder->build();
	}
}

?>
