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
        $_template = empty(Config::GeneralConfig()['theme']) ? 'default' : Config::GeneralConfig()['theme'];
        // Instantiate twig.
        return Twig::create(
            APP_PATH.'views/'.$_template,
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