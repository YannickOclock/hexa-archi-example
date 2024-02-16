<?php

use function DI\create;

use App\Controller\AuthController;
use App\Controller\CreatePostController;
use Domain\Auth\Port\UserRepositoryInterface;
use Domain\Auth\Tests\Adapters\PdoUserRepository;
use Domain\Blog\Port\PostRepositoryInterface;
use Domain\Blog\Tests\Adapters\PdoPostRepository;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

return [
    // Bind an interface to an implementation
    PostRepositoryInterface::class => create(PdoPostRepository::class),
    UserRepositoryInterface::class => create(PdoUserRepository::class),

    AltoRouter::class => function () {
        $router = new AltoRouter();
        if (array_key_exists('BASE_URI', $_SERVER)) {
            $router->setBasePath($_SERVER['BASE_URI']);
        } else { 
            $_SERVER['BASE_URI'] = '/';
        }
        // Liste des routes
        $router->map('GET|POST', '/login', [AuthController::class, 'handleRequest'], 'main-login');
        $router->map('GET|POST', '/', [CreatePostController::class, 'handleRequest'], 'main-home');

        return $router;
    },

    // Configure Twig
    Environment::class => function () {
        $loader = new FilesystemLoader(__DIR__ . '/../infra/Views');
        return new Environment($loader);
    },
];