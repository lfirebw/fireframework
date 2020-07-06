<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Slim\Views\Twig;
use Fframework\Core\Config;
return [
    Twig::class => function (ContainerInterface $container): Twig {
        $cache = false;
        if(isset(Config::GeneralConfig()['cache']) && Config::GeneralConfig()['cache'] !== false){
			$cache = ROOT. 'cache';
        }
        // Instantiate twig.
        return Twig::create(
            APP_PATH.'views',
            [
                // 'cache' => $preferences->getRootPath() . '/cache',
                'cache' => $cache,
                'auto_reload' => true,
                'debug' => false,
                'strict_variables' => false
            ]
        );
    },
];

?>