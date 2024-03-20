<?php

namespace App\Controller;

use AltoRouter;
use App\Presenter\Auth\TwigAuthPresenter;
use Domain\Auth\Exception\BadCredentialsAuthException;
use Domain\Auth\Exception\InvalidAuthPostDataException;
use Domain\Auth\Exception\NotFoundEmailAuthException;
use Domain\Auth\Port\SessionRepositoryInterface;
use Domain\Auth\UseCase\AuthRequest;
use Domain\Auth\UseCase\AuthUser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class AuthController
{
    public function __construct(
        protected Environment $twig,
        protected AltoRouter $router,
        protected SessionRepositoryInterface $sessionRepository
    ){}

    public function handleRequest(Request $request, AuthUser $useCase): Response
    {
        $presenter = new TwigAuthPresenter($this->twig, $this->router, $this->sessionRepository);

        $authRequest = new AuthRequest();
        $authRequest->email = $request->request->get('email') ?? '';
        $authRequest->password = $request->request->get('password') ?? '';
        $authRequest->isPosted = $request->isMethod('POST');

        $useCase->execute($authRequest, $presenter);

        if ($presenter->redirect()) {
            return new RedirectResponse($presenter->viewModel());
        }
        return new Response($presenter->viewModel());
    }

    public function logout(AuthUser $useCase): Response
    {
        $useCase->logout();
        return new RedirectResponse($this->router->generate('main-home'));
    }
}