<?php

use function DI\create;

use App\Pdo\PdoPostRepository;
use App\Pdo\PdoUserRepository;
use App\Session\SessionUserRepository;
use Domain\Auth\Port\SessionRepositoryInterface;
use Domain\Auth\Port\UserRepositoryInterface;
use Domain\Blog\Port\PostRepositoryInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

return [
    // Bind an interface to an implementation
    PostRepositoryInterface::class => create(PdoPostRepository::class),
    UserRepositoryInterface::class => create(PdoUserRepository::class),
    SessionRepositoryInterface::class => create(SessionUserRepository::class),

    AltoRouter::class => function () {
        $router = new AltoRouter();
        if (array_key_exists('BASE_URI', $_SERVER)) {
            $router->setBasePath($_SERVER['BASE_URI']);
        } else { 
            $_SERVER['BASE_URI'] = '/';
        }
        return $router;
    },

    // Configure Twig
    Environment::class => function () {
        $loader = new FilesystemLoader(__DIR__ . '/../infra/Views');
        return new Environment($loader);
    },
];