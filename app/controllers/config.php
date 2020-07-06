<?php
declare(strict_types=1);

use App\Controllers\indexController;
use Psr\Container\ContainerInterface;
use Slim\Views\Twig;

return [
	indexController::class => function (ContainerInterface $container): indexController {
        return new indexController($container->get(Twig::class));
    }
];

?>