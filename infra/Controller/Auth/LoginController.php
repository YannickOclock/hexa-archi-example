<?php

namespace App\Controller\Auth;

use AltoRouter;
use App\Presenter\Auth\TwigAuthLoginPresenter;
use Domain\Auth\Port\SessionRepositoryInterface;
use Domain\Auth\UseCase\Login\LoginRequest;
use Domain\Auth\UseCase\Login\LoginUser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class LoginController
{
    public function __construct(
        protected Environment $twig,
        protected AltoRouter $router,
        protected SessionRepositoryInterface $sessionRepository
    ) {
    }

    public function handleRequest(Request $request, LoginUser $useCase): Response
    {
        $presenter = new TwigAuthLoginPresenter($this->twig, $this->router, $this->sessionRepository);

        $loginRequest = new LoginRequest();
        $loginRequest->email = $request->request->get('email') ?? '';
        $loginRequest->password = $request->request->get('password') ?? '';
        $loginRequest->isPosted = $request->isMethod('POST');

        $useCase->execute($loginRequest, $presenter);

        if ($presenter->redirect()) {
            return new RedirectResponse($presenter->viewModel());
        }
        return new Response($presenter->viewModel());
    }

    /**
     * @throws \Exception
     */
    public function logout(LoginUser $useCase): Response
    {
        $useCase->logout();
        return new RedirectResponse($this->router->generate('main-home'));
    }
}
