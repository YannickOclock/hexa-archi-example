<?php

namespace Domain\Blog\UseCase;

use Assert\LazyAssertionException;
use Domain\Auth\Exception\IsNotAnAuthorException;
use Domain\Auth\Exception\NotLoggedException;
use Domain\Auth\Port\SessionRepositoryInterface;
use Domain\Blog\Entity\Post;
use Domain\Blog\Exception\InvalidPostDataException;
use Domain\Blog\Port\PostRepositoryInterface;
use PDOException;

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

        // L'utilisateur est un auteur
        // On ne peut que créer un post en brouillon

        $post = new Post(
            $data['title'] ?? '', 
            $data['content'] ?? '', 
            null
        );
        try {
            $this->validate($post);
            $this->postRepository->save($post);
            return $post;
        } catch (LazyAssertionException $e) {
            // On récupère l'ensemble des message d'erreur de la fonction validate
            throw InvalidPostDataException::withErrors($e->getErrorExceptions());
        } catch (PDOException $e) {
            throw InvalidPostDataException::withMessage("Problème dans la base SQL");
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
                ->null()
            ->verifyNow()
        ;
    }
}