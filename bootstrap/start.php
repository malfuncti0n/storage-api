<?php

//composer autoload
require __DIR__ . '/../vendor/autoload.php';

//configuration values
use Noodlehaus\Config;

$config = new Config(__DIR__ . '/../app/config');

//default container
$container = new \Slim\Container([
    'settings' => [
        'displayErrorDetails' => true,
        'db' => [
            'driver' => $config->get('mysql.driver'),
            'host' => $config->get('mysql.host'),
            'database'  => $config->get('mysql.database'),
            'username'  => $config->get('mysql.username'),
            'password'  => $config->get('mysql.password'),
            'charset'   => $config->get('mysql.charset'),
            'collation' => $config->get('mysql.collation'),
            'prefix'    => $config->get('mysql.prefix'),
        ],
        'determineRouteBeforeAppMiddleware' => true
    ],
]);
var_dump($container);

//database connections

$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function ($container) use ($capsule){
    return $capsule;
};

//initialized our app that extend slim
$app = new \App\App($container);



require __DIR__ . '/../app/routes.php';
