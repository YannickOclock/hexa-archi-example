<?php

use App\Controller\AuthController;
use App\Controller\CreatePostController;
use App\Controller\ErrorController;
use App\Utils\Dispatcher;
use Symfony\Component\HttpFoundation\Request;

$container = require __DIR__ . '/../app/bootstrap.php';

// Dispatch
$router = $container->get(AltoRouter::class);
$match = $router->match();
$dispatcher = new Dispatcher($match, [ErrorController::class, 'error404']);
$route = $dispatcher->dispatch();

$controller = $route[1];
$parameters = $route[2];

$parameters['request'] = Request::createFromGlobals();
$parameters['router'] = $router;

$response = $container->call($controller, $parameters);
$response->send();