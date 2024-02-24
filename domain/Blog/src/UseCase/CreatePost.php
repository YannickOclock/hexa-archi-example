<?php

namespace Domain\Blog\UseCase;

use Assert\LazyAssertionException;
use Domain\Auth\Exception\IsNotAnAuthorException;
use Domain\Auth\Exception\NotLoggedException;
use Domain\Auth\Port\SessionRepositoryInterface;
use Domain\Blog\Entity\Post;
use Domain\Blog\Exception\InvalidPostDataException;
use Domain\Blog\Port\PostRepositoryInterface;

use function Assert\lazy;

class CreatePost
{

    public function __construct(
        private PostRepositoryInterface $postRepository,
        private SessionRepositoryInterface $sessionRepository
    ) {
    }
    public function execute(array $data): ?Post
    {
        if(!$this->sessionRepository->isLogged()) {
            throw new NotLoggedException('Vous devez être connecté pour créer un post');
        }
        if(!$this->sessionRepository->isAuthor()) {
            throw new IsNotAnAuthorException('Il faut être auteur pour créer un post');
        }

        $post = new Post(
            $data['title'] ?? '', 
            $data['content'] ?? '', 
            $data['publishedAt'] ?? null
        );
        try {
            $this->validate($post);
            $this->postRepository->save($post);
            return $post;
        } catch (LazyAssertionException $e) {
            // On récupère l'ensemble des message d'erreur de la fonction validate
            throw InvalidPostDataException::withMessage($e->getMessage());
        }
    }

    protected function validate(Post $post): void
    {
        lazy()
            ->that($post->title)
                ->notBlank()
                ->minLength(5)
            ->that($post->content)
                ->notBlank()
                ->minLength(10)
            ->that($post->publishedAt)
                ->nullOr()
                ->isInstanceOf(\DateTimeInterface::class)
            ->verifyNow()    
        ;
    }
}