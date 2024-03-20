<?php

namespace Domain\Blog\UseCase\ListPost;

use Domain\Auth\Port\SessionRepositoryInterface;
use Domain\Blog\Port\PostRepositoryInterface;

readonly class ListPost
{

    public function __construct(
        private PostRepositoryInterface    $postRepository,
        private SessionRepositoryInterface $sessionRepository
    ) {
    }

    public function execute(ListPostPresenter $presenter): void
    {
        $response = new ListPostResponse();
        $this->checkAuthorization($response);
        $posts = $this->postRepository->findAll();
        $response->setPosts($posts);
        $presenter->present($response);
    }

    public function checkAuthorization(ListPostResponse $response): void
    {
        if(!$this->sessionRepository->isLogged()) {
            $response->addError('global', 'Vous devez être connecté pour voir les posts');
        }
        if(!$this->sessionRepository->isAuthor()) {
            $response->addError('global', 'Il faut être auteur pour voir les posts');
        }
    }
}