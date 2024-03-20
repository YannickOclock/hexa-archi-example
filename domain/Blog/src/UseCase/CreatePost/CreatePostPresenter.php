<?php

namespace Domain\Blog\UseCase\CreatePost;

interface CreatePostPresenter
{
    public function present(CreatePostResponse $response): void;
}
