<?php

use function DI\create;

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

    // Configure Twig
    Environment::class => function () {
        $loader = new FilesystemLoader(__DIR__ . '/../infra/Views');
        return new Environment($loader);
    },
];