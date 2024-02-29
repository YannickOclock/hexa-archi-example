<?php

namespace Domain\Blog\Entity;

use DateTimeInterface;
use Domain\Blog\ObjectValue\StatusPost;

class Post
{
    public string $title;
    public string $content;
    public string $uuid;
    public StatusPost $status;
    public ?DateTimeInterface $publishedAt;

    public function __construct(string $title = '', string $content = '', ?DateTimeInterface $publishedAt = null, string $uuid = null)
    {
        $this->title = $title;
        $this->content = $content;
        $this->publishedAt = $publishedAt;
        $this->uuid = $uuid ?: uniqid();

        $statusCode = $publishedAt !== null ? StatusPost::PUBLISHED : StatusPost::DRAFT;
        $this->status = new StatusPost($statusCode);
    }
}