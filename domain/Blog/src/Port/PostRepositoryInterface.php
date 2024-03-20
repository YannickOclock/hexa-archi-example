<?php

namespace Domain\Blog\Port;

use Domain\Blog\Entity\Post;

interface PostRepositoryInterface
{
    public function save(Post $post): bool;
    public function find(string $uuid): ?Post;
    public function findAll(): array;
}
