<?php

namespace Domain\Blog\Tests\Adapters;

use Domain\App\Tests\Adapters\PdoTestRepository;
use Domain\Blog\Entity\Post;
use Domain\Blog\Port\PostRepositoryInterface;
use PDO;

class PdoPostRepository implements PostRepositoryInterface
{
    private PDO $pdo;
    public function __construct()
    {
        $this->pdo = PdoTestRepository::getPDO();
    }

    public function save(Post $post): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO posts (uuid, title, content, published_at) VALUES (:uuid, :title, :content, :published_at)');
        $stmt->execute([
            'uuid' => $post->uuid,
            'title' => $post->title,
            'content' => $post->content,
            'published_at' => $post->publishedAt ? $post->publishedAt->format('Y-m-d H:i:s') : null,
        ]);
    }

    public function find(string $uuid): ?Post
    {
        $stmt = $this->pdo->prepare('SELECT * FROM posts WHERE uuid = :uuid');
        $stmt->execute(['uuid' => $uuid]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            return null;
        }
        return new Post($row['title'], $row['content'], $row['published_at'] ? new \DateTime($row['published_at']) : null, $row['uuid']);
    }
}
