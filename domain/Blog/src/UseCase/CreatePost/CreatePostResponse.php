<?php

namespace Domain\Blog\UseCase\CreatePost;

use Domain\App\Error\Notification;
use Domain\Blog\Entity\Post;

class CreatePostResponse
{
    private Notification $notification;
    private ?Post $post = null;

    public function __construct(
    ) {
        $this->notification = new Notification();
    }

    public function setPost(Post $post): void
    {
        $this->post = $post;
    }

    public function post(): ?Post
    {
        return $this->post;
    }

    public function addError(string $fieldName, string $error): void
    {
        $this->notification->addError($fieldName, $error);
    }

    public function notification(): Notification
    {
        return $this->notification;
    }
}
