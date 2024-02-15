<?php

namespace Domain\Blog\UseCase;

use Assert\LazyAssertionException;
use Domain\Blog\Entity\Post;
use Domain\Blog\Exception\InvalidPostDataException;
use Domain\Blog\Port\PostRepositoryInterface;

use function Assert\lazy;

class CreatePost
{

    public function __construct(private PostRepositoryInterface $postRepository)
    {
    }
    public function execute(array $data): ?Post
    {
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