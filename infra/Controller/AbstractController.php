<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

abstract class AbstractController {
    public function __construct(
        protected Environment $twig
    ){}
    public function render(string $template, array $data = [])
    {
        return new Response($this->twig->render($template, $data));
    }
    public function redirectToRoute(string $route, array $params = [])
    {
        return new Response("Redirect to $route");
    }
}