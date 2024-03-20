<?php

namespace Domain\Blog\UseCase\ListPost;

use Domain\App\Error\Notification;
use Domain\Blog\Entity\Post;

class ListPostResponse
{
    private Notification $notification;

    /** @var Post[] $posts */
    private array $posts = [];

    public function __construct(
    ) {
        $this->notification = new Notification();
    }

    public function setPosts(array $posts): void
    {
        $this->posts = $posts;
    }

    public function posts(): array
    {
        return $this->posts;
    }

    public function addError(string $fieldName, string $error)
    {
        $this->notification->addError($fieldName, $error);
    }

    public function notification(): Notification
    {
        return $this->notification;
    }
}
