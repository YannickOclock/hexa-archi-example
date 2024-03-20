<?php

use Domain\Auth\Entity\SessionUser;
use Domain\Auth\Exception\IsNotAPublisherException;
use Domain\Auth\Exception\NotLoggedException;
use Domain\Auth\Port\SessionRepositoryInterface;
use Domain\Auth\Tests\Adapters\InMemorySessionUserRepository;
use Domain\Blog\Entity\Post;
use Domain\Blog\Exception\InvalidPostDataException;
use Domain\Blog\ObjectValue\StatusPost;
use Domain\Blog\Tests\Adapters\InMemoryPostRepository;
use Domain\Blog\UseCase\CreateAndPublishPost;

class CreateAndPublishPostAuth {
    static public function loginAsPublisher(): SessionRepositoryInterface
    {
        $sessionRepository = new InMemorySessionUserRepository();
        $sessionRepository->saveUser(new SessionUser('john@doe.fr', ['publisher']));
        return $sessionRepository;
    }

    static public function loginAsAuthor(): SessionRepositoryInterface
    {
        $sessionRepository = new InMemorySessionUserRepository();
        $sessionRepository->saveUser(new SessionUser('john@doe.fr', ['author']));
        return $sessionRepository;
    }

    static public function createSuccessfullPostAsPublisher()
    {
        $repository = new InMemoryPostRepository();
        $useCase = new CreateAndPublishPost($repository, self::loginAsPublisher());

        $post = $useCase->execute([
            'title' => 'My first post',
            'content' => 'This is my first post content',
        ]);

        return [
            'post' => $post,
            'repository' => $repository
        ];
    }

    static public function createPostAsAuthor()
    {
        $repository = new InMemoryPostRepository();
        $useCase = new CreateAndPublishPost($repository, self::loginAsAuthor());

        $post = $useCase->execute([
            'title' => 'My first post',
            'content' => 'This is my first post content',
        ]);

        return [
            'post' => $post,
            'repository' => $repository
        ];
    }

    static public function createPostWithNoCredentials()
    {
        $repository = new InMemoryPostRepository();
        $useCase = new CreateAndPublishPost($repository, new InMemorySessionUserRepository());

        $post = $useCase->execute([
            'title' => 'My first post',
            'content' => 'This is my first post content',
        ]);

        return [
            'post' => $post,
            'repository' => $repository
        ];
    }
}

it("should throw a NotLoggedException", function () {
    $this->expectException(NotLoggedException::class);
    ['post' => $post, 'repository' => $repository] = CreateAndPublishPostAuth::createPostWithNoCredentials();
});

it("should throw a IsNotAPublisherException", function () {
    $this->expectException(IsNotAPublisherException::class);
    ['post' => $post, 'repository' => $repository] = CreateAndPublishPostAuth::createPostAsAuthor();
});

it("should create a post (after authenticate)", function () {
    ['post' => $post, 'repository' => $repository] = CreateAndPublishPostAuth::createSuccessfullPostAsPublisher();
    $this->assertInstanceOf(Post::class, $post);
    $this->assertEquals($post, $repository->find($post->uuid));
});

it("should be a published post (on create)", function () {
    ['post' => $post, 'repository' => $repository] = CreateAndPublishPostAuth::createSuccessfullPostAsPublisher();
    $this->assertEquals($post->status->getStatus(), StatusPost::PUBLISHED);
});

it("should throw an InvalidPostException if the post is invalid", function ($postData) {
    $sessionRepository = new InMemorySessionUserRepository();
    $sessionRepository->saveUser(new SessionUser('john@doe.fr', ['publisher']));

    $repository = new InMemoryPostRepository();
    $useCase = new CreateAndPublishPost($repository, $sessionRepository);
    $useCase->execute($postData);
})->with([
    [['title' => 'My first post']],
    [['content' => 'This is my first post content']],
    [[]]
])->expectException(InvalidPostDataException::class);