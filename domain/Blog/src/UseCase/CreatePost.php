<?php

namespace Domain\Blog\UseCase;

use Domain\Blog\Entity\Post;

class CreatePost
{
    public function execute(array $data): ?Post
    {
        return new Post($data['title'], $data['content'], $data['publishedAt']);
    }
}