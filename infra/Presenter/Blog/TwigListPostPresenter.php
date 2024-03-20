<?php

namespace App\Presenter\Blog;

use AltoRouter;
use Domain\Auth\Port\SessionRepositoryInterface;
use Domain\Blog\UseCase\ListPost\ListPostPresenter;
use Domain\Blog\UseCase\ListPost\ListPostResponse;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class TwigListPostPresenter implements ListPostPresenter
{
    private string $viewmodel;

    public function __construct(
        protected Environment $twig,
        protected AltoRouter $router,
        protected SessionRepositoryInterface $sessionRepository
    ) {
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function present(ListPostResponse $response): void
    {
        $data = [
            'router' => $this->router,
            'session' => $this->sessionRepository,
            'notification' => $response->notification(),
            'posts' => $response->posts()
        ];

        $this->viewmodel = $this->twig->render('blog/list.html.twig', $data);
    }

    public function viewModel(): string
    {
        return $this->viewmodel;
    }
}
