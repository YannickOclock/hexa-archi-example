<?php

namespace Domain\Blog\Tests;

use Domain\Blog\Tests\Adapters\InMemoryPostRepository;
use Domain\Blog\Tests\Mock\SessionUserRepositoryMock;
use Domain\Blog\Tests\RequestBuilder\CreatePostRequestBuilder;
use Domain\Blog\UseCase\CreatePost\CreatePost;
use Domain\Blog\UseCase\CreatePost\CreatePostPresenter;
use Domain\Blog\UseCase\CreatePost\CreatePostResponse;
use PHPUnit\Framework\TestCase;

class CreatePostTest extends TestCase implements CreatePostPresenter
{
    private CreatePostResponse $response;
    private InMemoryPostRepository $postRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->postRepository = new InMemoryPostRepository();
    }

    public function present(CreatePostResponse $response): void
    {
        $this->response = $response;
    }

    // tests
    public function testCreateSuccessfullPost()
    {
        $sessionUserRepository = SessionUserRepositoryMock::mockLoginAsAuthor();
        $useCase = new CreatePost($this->postRepository, $sessionUserRepository);
        $request = CreatePostRequestBuilder::aRequest()
            ->withTitle('My first post')
            ->withContent('This is my first post content')
            ->isPosted(true)
            ->build();
        $useCase->execute($request, $this);
        $this->assertEmpty($this->response->notification()->getErrors());
    }

    public function testCreatePostWithNoCredentials()
    {
        $sessionUserRepository = SessionUserRepositoryMock::MockNoUser();
        $useCase = new CreatePost($this->postRepository, $sessionUserRepository);
        $request = CreatePostRequestBuilder::aRequest()
            ->withTitle('My first post')
            ->withContent('This is my first post content')
            ->isPosted(true)
            ->build();
        $useCase->execute($request, $this);
        $this->assertGreaterThan(1, count($this->response->notification()->getErrorsFor('global')));
    }

    public function testCreatePostWithInvalidData()
    {
        $sessionUserRepository = SessionUserRepositoryMock::mockLoginAsAuthor();
        $useCase = new CreatePost($this->postRepository, $sessionUserRepository);
        $request = CreatePostRequestBuilder::aRequest()
            ->withTitle('')
            ->withContent('')
            ->isPosted(true)
            ->build();
        $useCase->execute($request, $this);
        $this->assertGreaterThan(1, count($this->response->notification()->getErrorsFor('title')));
        $this->assertGreaterThan(1, count($this->response->notification()->getErrorsFor('content')));
    }
}