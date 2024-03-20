<?php

namespace App\Controller\Blog;

use AltoRouter;
use App\Presenter\Blog\TwigCreatePostPresenter;
use Domain\Auth\Port\SessionRepositoryInterface;
use Domain\Blog\UseCase\CreatePost\CreatePost;
use Domain\Blog\UseCase\CreatePost\CreatePostRequest;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class CreatePostController
{
    public function __construct(
        protected Environment $twig,
        protected AltoRouter $router,
        protected SessionRepositoryInterface $sessionRepository
    ) {
    }

    public function handleRequest(Request $request, CreatePost $useCase): Response
    {
        $presenter = new TwigCreatePostPresenter($this->twig, $this->router, $this->sessionRepository);

        $createPostRequest = new CreatePostRequest();
        $createPostRequest->title = $request->request->get('title') ?? '';
        $createPostRequest->content = $request->request->get('content') ?? '';
        $createPostRequest->isPosted = $request->isMethod('POST');

        $useCase->execute($createPostRequest, $presenter);

        if ($presenter->redirect()) {
            return new RedirectResponse($presenter->viewModel());
        }
        return new Response($presenter->viewModel());
    }
}
