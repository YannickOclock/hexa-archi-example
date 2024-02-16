<?php
    namespace Domain\Blog\Tests\Adapters;

    use Domain\Blog\Entity\Post;
    use Domain\Blog\Port\PostRepositoryInterface;

    class InMemoryPostRepository implements PostRepositoryInterface
    {
        private array $posts = [];

        public function save(Post $post): void
        {
            $this->posts[$post->uuid] = $post;
        }

        public function find(string $uuid): ?Post
        {
            return $this->posts[$uuid] ?? null;
        }
    }