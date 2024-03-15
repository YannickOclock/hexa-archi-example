<?php

use App\Controller\AuthController;
use App\Controller\CreatePostController;
use App\Controller\ErrorController;
use App\Utils\Dispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Dotenv\Dotenv;

$container = require __DIR__ . '/../app/bootstrap.php';
$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/../.env');

// Dispatch
$router = $container->get(AltoRouter::class);

// Liste des routes
$router->map('GET|POST', '/login', [AuthController::class, 'handleRequest'], 'main-login');
$router->map('GET|POST', '/', [CreatePostController::class, 'handleRequest'], 'main-home');

$match = $router->match();
$dispatcher = new Dispatcher($match, [ErrorController::class, 'error404']);
$route = $dispatcher->dispatch();

$controller = $route[1];
$parameters = $route[2];

$parameters['request'] = Request::createFromGlobals();
$parameters['router'] = $router;

$response = $container->call($controller, $parameters);
$response->send();