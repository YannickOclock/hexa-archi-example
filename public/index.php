<?php

use App\Controller\AuthController;
use App\Controller\CreatePostController;
use App\Controller\ErrorController;
use App\Utils\Dispatcher;
use Symfony\Component\HttpFoundation\Request;

$container = require __DIR__ . '/../app/bootstrap.php';

$router = new AltoRouter();

if (array_key_exists('BASE_URI', $_SERVER)) {
    $router->setBasePath($_SERVER['BASE_URI']);
} else { 
    $_SERVER['BASE_URI'] = '/';
}

// Liste des routes
$router->map('GET|POST', '/login', [AuthController::class, 'handleRequest'], 'main-login');
$router->map('GET|POST', '/', [CreatePostController::class, 'handleRequest'], 'main-home');

// Dispatch
$match = $router->match();
$dispatcher = new Dispatcher($match, [ErrorController::class, 'error404']);
$route = $dispatcher->dispatch();

$controller = $route[1];
$parameters = $route[2];

$parameters['request'] = Request::createFromGlobals();
$parameters['router'] = $router;

$response = $container->call($controller, $parameters);
$response->send();