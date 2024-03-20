<?php

namespace App\Controller;

use AltoRouter;
use Domain\Auth\Port\SessionRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

abstract class AbstractController
{
    public function __construct(
        protected Environment $twig,
        protected AltoRouter $router,
        protected SessionRepositoryInterface $sessionRepository
    ) {
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function render(string $template, array $data = []): Response
    {
        $data['router'] = $this->router;
        $data['session'] = $this->sessionRepository;
        return new Response($this->twig->render($template, $data));
    }

    /**
     * @throws \Exception
     */
    public function redirectToRoute(string $route, array $params = []): void
    {
        header('Location: ' . $this->router->generate($route, $params));
    }
}
