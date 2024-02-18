<?php

namespace App\Controller;

use AltoRouter;
use Domain\Auth\Exception\BadCredentialsAuthException;
use Domain\Auth\Exception\InvalidAuthPostDataException;
use Domain\Auth\Exception\NotFoundEmailAuthException;
use Domain\Auth\UseCase\AuthUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends AbstractController
{
    public function handleRequest(Request $request, AuthUser $useCase)
    {
        if($request->isMethod('GET')) {
            return $this->render('auth.form.html.twig');
        }

        // traiter le formulaire en utilisant le use case
        try {
            $useCase->execute([
                'email' => $request->request->get('email', ''), 
                'password' => $request->request->get('password', '')
            ]);
        } catch (InvalidAuthPostDataException | BadCredentialsAuthException | NotFoundEmailAuthException $e) {
            return $this->render('auth.form.html.twig', [
                'errors' => $e->getErrors()
            ]);
        }

        $this->redirectToRoute('main-home');
    }
}