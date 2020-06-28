<?php
declare(strict_types=1);

namespace Fframework\Core;

use Illuminate\Database\Capsule\Manager as Capsule;

class Database{
    public function __construct(Array $arr) {
        //initialize connection
        $capsule = new Capsule;
        $capsule->addConnection([
        'driver' => $arr['driver'],
        'host' => $arr['host'],
        'database' => $arr['database'],
        'username' => $arr['username'],
        'password' => $arr['password'],
        'charset' => $arr['charset'],
        'collation' => $arr['collation'],
        'prefix' => $arr['prefix']
        ]);
        // Setup the Eloquent ORM… 
        $capsule->bootEloquent();
    }
}

?>