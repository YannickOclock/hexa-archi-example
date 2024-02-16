<?php

include __DIR__ . '/vendor/autoload.php';

use App\Controller\CreatePostController;
use Domain\Blog\Tests\Adapters\PdoPostRepository;
use Domain\Blog\UseCase\CreatePost;
use Symfony\Component\HttpFoundation\Request;

$request = Request::createFromGlobals();

$pdoPostRepository = new PdoPostRepository();
$useCase = new CreatePost($pdoPostRepository);
// $controller = new CreatePostController($useCase);
// $response = $controller->handleRequest($request);
// $response->send();
