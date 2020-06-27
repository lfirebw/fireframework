<?php
    declare(strict_types=1);

    namespace App;
    
    use Fframework\Core\Route;
    use App\Controllers\indexController;
    use Slim\Routing\RouteCollectorProxy;
	
    
    Route::App()->group('/', function (RouteCollectorProxy $group) {
        $group->get('', indexController::class . ':index')->setName('index');
    });
?>