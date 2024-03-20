<?php

namespace App\Pdo;

use Domain\Blog\Entity\Post;
use Domain\Blog\Port\PostRepositoryInterface;
use PDO;
use PDOException;

class PdoPostRepository implements PostRepositoryInterface
{
    private PDO $pdo;
    public function __construct()
    {
        $this->pdo = PdoRepository::getPDO();
    }

    public function save(Post $post): bool
    {
        try {
            $stmt = $this->pdo->prepare('INSERT INTO posts (uuid, title, content, published_at) VALUES (:uuid, :title, :content, :published_at)');
            return $stmt->execute([
                'uuid' => $post->uuid,
                'title' => $post->title,
                'content' => $post->content,
                'published_at' => $post->publishedAt ? $post->publishedAt->format('Y-m-d H:i:s') : null,
            ]);
        } catch (PDOException $e) {
            return false;
        }
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

    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM posts');
        $posts = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $posts[] = new Post($row['title'], $row['content'], $row['published_at'] ? new \DateTime($row['published_at']) : null, $row['uuid']);
        }
        return $posts;
    }
}
