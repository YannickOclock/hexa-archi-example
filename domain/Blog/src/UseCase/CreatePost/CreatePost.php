<?php

namespace Domain\Blog\UseCase\CreatePost;

use Assert\LazyAssertionException;
use Domain\Auth\Port\SessionRepositoryInterface;
use Domain\Blog\Entity\Post;
use Domain\Blog\Port\PostRepositoryInterface;

use function Assert\lazy;

readonly class CreatePost
{
    public function __construct(
        private PostRepositoryInterface    $postRepository,
        private SessionRepositoryInterface $sessionRepository
    ) {
    }

    public function execute(CreatePostRequest $request, CreatePostPresenter $presenter): void
    {
        $response = new CreatePostResponse();

        if($request->isPosted) {
            $this->createPost($request, $response);
        }

        $presenter->present($response);
    }

    private function createPost(CreatePostRequest $request, CreatePostResponse $response): void
    {
        if(!$this->sessionRepository->isLogged()) {
            $response->addError('global', 'Vous devez être connecté pour créer un post');
        }
        if(!$this->sessionRepository->isAuthor()) {
            $response->addError('global', 'Il faut être auteur pour créer un post');
        }

        // L'utilisateur est un auteur
        // On ne peut que créer un post en brouillon

        $post = new Post($request->title ?? '', $request->content ?? '', null);

        // valider les données du formulaire en premier
        $isValid = $this->validateRequest($request, $response);
        $isValid = $isValid && $this->postRepository->save($post);

        if(!$isValid) {
            $response->addError('global', 'Une erreur est survenue lors de la création du post');
        } else {
            $response->setPost($post);
        }

    }

    protected function validateRequest(CreatePostRequest $request, CreatePostResponse $response): bool
    {
        try {
            lazy()
                ->tryAll()
                ->that($request->title, 'title')
                    ->notBlank("Le titre ne doit pas être vide")
                    ->minLength(5, "Le titre doit faire au moins 5 caractères")
                ->that($request->content, 'content')
                    ->notBlank("Le contenu ne doit pas être vide")
                    ->minLength(10, "Le contenu doit faire au moins 10 caractères")
                ->that($request->publishedAt, 'publishedAt')
                    ->null()
                ->verifyNow()
            ;
            return true;
        } catch (LazyAssertionException $e) {
            foreach($e->getErrorExceptions() as $error) {
                $response->addError($error->getPropertyPath(), $error->getMessage());
            }
            return false;
        }
    }
}
