<?php

namespace App\Controller\Blog;

use AltoRouter;
use App\Presenter\Blog\TwigListPostPresenter;
use Domain\Auth\Port\SessionRepositoryInterface;
use Domain\Blog\UseCase\ListPost\ListPost;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class ListPostController
{
    public function __construct(
        protected Environment $twig,
        protected AltoRouter $router,
        protected SessionRepositoryInterface $sessionRepository
    ) {
    }

    public function handleRequest(ListPost $useCase): Response
    {
        $presenter = new TwigListPostPresenter($this->twig, $this->router, $this->sessionRepository);
        $useCase->execute($presenter);
        return new Response($presenter->viewModel());
    }
}
