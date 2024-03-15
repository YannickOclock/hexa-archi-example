<?php

namespace App\Controller;

use Domain\Auth\Port\SessionRepositoryInterface;
use Domain\Blog\UseCase\CreatePost;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreatePostController extends AbstractController
{
    public function handleRequest(CreatePost $useCase, Request $request)
    {
        $session = $this->sessionRepository;
        if(!$session->isLogged() || !$session->isAuthor()) {
            return $this->redirectToRoute('main-login');
        }

        if($request->isMethod('GET')) {
            return $this->render('form.html.twig');
        }

        // traiter le formulaire en utilisant le use case
        $post = $useCase->execute([
            'title' => $request->request->get('title', ''), 
            'content' => $request->request->get('content', ''),
            'publishedAt' => $request->request->has('is_published') ? new \DateTime() : null
        ]);
        return new Response("<h1>{$post->title}</h1>", 201);
    }
}
