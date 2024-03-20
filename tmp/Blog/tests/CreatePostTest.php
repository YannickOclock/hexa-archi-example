<?php

use Domain\Auth\Entity\SessionUser;
use Domain\Auth\Exception\NotLoggedException;
use Domain\Auth\Port\SessionRepositoryInterface;
use Domain\Auth\Tests\Adapters\InMemorySessionUserRepository;
use Domain\Blog\Entity\Post;
use Domain\Blog\Exception\InvalidPostDataException;
use Domain\Blog\ObjectValue\StatusPost;
use Domain\Blog\Tests\Adapters\InMemoryPostRepository;
use Domain\Blog\UseCase\CreatePost;

function loginAsAuthor(): SessionRepositoryInterface
{
    $sessionRepository = new InMemorySessionUserRepository();
    $sessionRepository->saveUser(new SessionUser('john@doe.fr', ['author']));
    return $sessionRepository;
}

function createSuccessfullPost()
{
    $repository = new InMemoryPostRepository();
    $useCase = new CreatePost($repository, loginAsAuthor());

    $post = $useCase->execute([
        'title' => 'My first post',
        'content' => 'This is my first post content',
    ]);

    return [
        'post' => $post,
        'repository' => $repository
    ];
}

function createPostWithNoCredentials()
{
    $repository = new InMemoryPostRepository();
    $useCase = new CreatePost($repository, new InMemorySessionUserRepository());

    $post = $useCase->execute([
        'title' => 'My first post',
        'content' => 'This is my first post content',
    ]);

    return [
        'post' => $post,
        'repository' => $repository
    ];
}

it("should throw a NotLoggedException", function () {
    $this->expectException(NotLoggedException::class);
    ['post' => $post, 'repository' => $repository] = createPostWithNoCredentials();
});

it("should create a post (after authenticate)", function () {
    ['post' => $post, 'repository' => $repository] = createSuccessfullPost();
    $this->assertInstanceOf(Post::class, $post);
    $this->assertEquals($post, $repository->find($post->uuid));
});

it("should be a draft post (on create)", function () {
    ['post' => $post, 'repository' => $repository] = createSuccessfullPost();
    $this->assertEquals($post->status->getStatus(), StatusPost::DRAFT);
});

it("should throw an InvalidPostException if the post is invalid", function ($postData) {
    $sessionRepository = new InMemorySessionUserRepository();
    $sessionRepository->saveUser(new SessionUser('john@doe.fr', ['author']));

    $repository = new InMemoryPostRepository();
    $useCase = new CreatePost($repository, $sessionRepository);
    $useCase->execute($postData);
})->with([
    [['title' => 'My first post']],
    [['content' => 'This is my first post content']],
    [[]]
])->expectException(InvalidPostDataException::class);