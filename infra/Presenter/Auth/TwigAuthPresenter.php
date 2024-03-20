<?php
    namespace App\Presenter\Auth;

    use AltoRouter;
    use Domain\Auth\Port\SessionRepositoryInterface;
    use Domain\Auth\UseCase\AuthPresenter;
    use Domain\Auth\UseCase\AuthResponse;
    use Twig\Environment;

    class TwigAuthPresenter implements AuthPresenter
    {
        private string $viewmodel;
        private bool $redirect = false;

        public function __construct(
            protected Environment $twig,
            protected AltoRouter $router,
            protected SessionRepositoryInterface $sessionRepository
        ) {
        }

        public function present(AuthResponse $response): void
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
                $this->viewmodel = $this->twig->render('auth.form.html.twig', $data);
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