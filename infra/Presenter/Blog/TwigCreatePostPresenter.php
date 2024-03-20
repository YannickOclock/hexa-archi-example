<?php

namespace App\Presenter\Blog;

use AltoRouter;
use Domain\Auth\Port\SessionRepositoryInterface;
use Domain\Blog\UseCase\CreatePost\CreatePostPresenter;
use Domain\Blog\UseCase\CreatePost\CreatePostResponse;
use Exception;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class TwigCreatePostPresenter implements CreatePostPresenter
{
    private string $viewmodel;
    private bool $redirect = false;

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
     * @throws Exception
     */
    public function present(CreatePostResponse $response): void
    {
        $data = [
            'router' => $this->router,
            'session' => $this->sessionRepository,
            'notification' => $response->notification()
        ];

        if ($response->post()) {
            $this->redirect = true;
            $this->viewmodel = $this->router->generate('main-home');
        } else {
            $this->viewmodel = $this->twig->render('blog/form.html.twig', $data);
        }
    }

    public function viewModel(): string
    {
        return $this->viewmodel;
    }

    public function redirect(): bool
    {
        return $this->redirect;
    }
}
