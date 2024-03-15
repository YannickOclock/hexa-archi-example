<?php

namespace Domain\Blog\UseCase;

use Assert\LazyAssertionException;
use Domain\Auth\Exception\IsNotAnAuthorException;
use Domain\Auth\Exception\IsNotAPublisherException;
use Domain\Auth\Exception\NotLoggedException;
use Domain\Auth\Port\SessionRepositoryInterface;
use Domain\Blog\Entity\Post;
use Domain\Blog\Exception\InvalidPostDataException;
use Domain\Blog\Port\PostRepositoryInterface;

use function Assert\lazy;

class CreateAndPublishPost
{

    public function __construct(
        private PostRepositoryInterface $postRepository,
        private SessionRepositoryInterface $sessionRepository
    ) {
    }
    public function execute(array $data): ?Post
    {
        if(!$this->sessionRepository->isLogged()) {
            throw new NotLoggedException('Vous devez être connecté pour créer et publier un post');
        }
        if(!$this->sessionRepository->isPublisher()) {
            throw new IsNotAPublisherException('Il faut être éditeur pour créer et publier un post');
        }

        // Possibilité de passer une date de publication (spécifiée dans le formulaire de création de post)
        // Si la date n'est pas passée, on prend la date du jour
        $date = $data['publishedAt'] ?? new \DateTime('now');

        $post = new Post(
            $data['title'] ?? '', 
            $data['content'] ?? '', 
            $date
        );
        try {
            $this->validate($post);
            $this->postRepository->save($post);
            return $post;
        } catch (LazyAssertionException $e) {
            // On récupère l'ensemble des message d'erreur de la fonction validate
            throw InvalidPostDataException::withErrors($e->getErrorExceptions());
        }
    }

    protected function validate(Post $post): void
    {
        lazy()
            ->that($post->title, 'title')
                ->notBlank("Le titre ne doit pas être vide")
                ->minLength(5, "Le titre doit faire au moins 5 caractères")
            ->that($post->content, 'content')
                ->notBlank("Le contenu ne doit pas être vide")
                ->minLength(10, "Le contenu doit faire au moins 10 caractères")
            ->that($post->publishedAt, 'publishedAt')
                ->nullOr()
                ->isInstanceOf(\DateTimeInterface::class)
            ->verifyNow()    
        ;
    }
}