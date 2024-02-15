<?php

use Domain\Blog\Entity\Post;
use Domain\Blog\Exception\InvalidPostDataException;
use Domain\Blog\Tests\Adapters\InMemoryPostRepository;
use Domain\Blog\Tests\Adapters\PdoPostRepository;
use Domain\Blog\UseCase\CreatePost;

it("should create a post", function () {
    $repository = new PdoPostRepository();
    $useCase = new CreatePost($repository);

    $post = $useCase->execute([
        'title' => 'My first post',
        'content' => 'This is my first post content',
        'publishedAt' => new DateTime('2020-01-01 00:00:00')
    ]);

    $this->assertInstanceOf(Post::class, $post);
    $this->assertEquals($post, $repository->find($post->uuid));
});

it("should throw an InvalidPostException if the post is invalid", function ($postData) {
    $repository = new InMemoryPostRepository();
    $useCase = new CreatePost($repository);
    $useCase->execute($postData);
})->with([
    [['title' => 'My first post', 'publishedAt' => new DateTime('2020-01-01 00:00:00')]],
    [['publishedAt' => new DateTime('2020-01-01 00:00:00')]],
    [[]]
])->expectException(InvalidPostDataException::class);