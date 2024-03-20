<?php

namespace Domain\Blog\UseCase\CreatePost;

use DateTime;
use Domain\App\Error\Notification;

class CreatePostRequest {
    public string $title = '';
    public string $content = '';
    public ?string $publishedAt = null;
    public bool $isPosted = false;
}