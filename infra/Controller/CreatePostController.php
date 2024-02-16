<?php

namespace App\Controller;

use Domain\Blog\UseCase\CreatePost;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class CreatePostController
{
    public function __construct(
        protected CreatePost $useCase,
        protected Environment $twig
    ){}

    public function handleRequest(Request $request)
    {
        if($request->isMethod('GET')) {
            return $this->renderForm();
        }

        // traiter le formulaire en utilisant le use case
        $post = $this->useCase->execute([
            'title' => $request->request->get('title', ''), 
            'content' => $request->request->get('content', ''),
            'publishedAt' => $request->request->has('is_published') ? new \DateTime() : null
        ]);
        return new Response("<h1>{$post->title}</h1>", 201);
    }

    public function renderForm()
    {
        return new Response($this->twig->render('form.html.twig', []));
    }
}
