<?php

namespace Domain\Blog\Tests\RequestBuilder;

use Domain\Blog\UseCase\CreatePost\CreatePostRequest;

class CreatePostRequestBuilder
{
    private CreatePostRequest $request;

    public function __construct()
    {
        $this->request = new CreatePostRequest();
    }

    public function withTitle(string $title): CreatePostRequestBuilder
    {
        $this->request->title = $title;
        return $this;
    }

    public function withContent(string $content): CreatePostRequestBuilder
    {
        $this->request->content = $content;
        return $this;
    }

    public function isPosted(bool $isPosted): CreatePostRequestBuilder
    {
        $this->request->isPosted = $isPosted;
        return $this;
    }

    public function build(): CreatePostRequest
    {
        return $this->request;
    }

    public static function aRequest(): CreatePostRequestBuilder
    {
        return new CreatePostRequestBuilder();
    }
}