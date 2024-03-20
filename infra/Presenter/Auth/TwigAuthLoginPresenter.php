<?php

namespace App\Presenter\Auth;

use AltoRouter;
use Domain\Auth\Port\SessionRepositoryInterface;
use Domain\Auth\UseCase\Login\LoginPresenter;
use Domain\Auth\UseCase\Login\LoginResponse;
use Exception;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class TwigAuthLoginPresenter implements LoginPresenter
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
    public function present(LoginResponse $response): void
    {
        $data = [
            'router' => $this->router,
            'session' => $this->sessionRepository,
            'notification' => $response->notification()
        ];

        if ($response->isAuthenticated()) {
            $this->redirect = true;
            $this->viewmodel = $this->router->generate('main-home');
        } else {
            $this->viewmodel = $this->twig->render('auth/form.html.twig', $data);
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
