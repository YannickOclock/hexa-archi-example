<?php
namespace App\Controller;

use AltoRouter;
use Domain\Auth\Port\SessionRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

abstract class AbstractController {
    public function __construct(
        protected Environment $twig,
        protected AltoRouter $router,
        protected SessionRepositoryInterface $sessionRepository
    ){}
    public function render(string $template, array $data = [])
    {
        $data['router'] = $this->router;
        $data['session'] = $this->sessionRepository;
        return new Response($this->twig->render($template, $data));
    }
    public function redirectToRoute(string $route, array $params = [])
    {
        header('Location: ' . $this->router->generate($route, $params));
    }
}