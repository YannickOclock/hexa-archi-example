<?php

use App\Controller\CreatePostController;
use App\Utils\Dispatcher;
use Symfony\Component\HttpFoundation\Request;

$container = require __DIR__ . '/../app/bootstrap.php';

$router = new AltoRouter();

if (array_key_exists('BASE_URI', $_SERVER)) {
    $router->setBasePath($_SERVER['BASE_URI']);
} else { 
    $_SERVER['BASE_URI'] = '/';
}

$router->map(
    'GET|POST', '/', 
    ['method' => 'handleRequest','controller' => CreatePostController::class], 'main-home'
);

$match = $router->match();
$dispatcher = new Dispatcher($match, '\App\Controllers\ErrorController::err404');
$route = $dispatcher->dispatch();

$controller = $route[1];
$parameters = $route[2];

$parameters['request'] = Request::createFromGlobals();

$response = $container->call($controller, $parameters);
$response->send();