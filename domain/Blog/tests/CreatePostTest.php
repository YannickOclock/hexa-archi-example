<?php

use Domain\Blog\Entity\Post;
use Domain\Blog\UseCase\CreatePost;

    it("should create a post", function () {
        $useCase = new CreatePost();
        
        $post = $useCase->execute([
            'title' => 'My first post',
            'content' => 'This is my first post content',
            'publishedAt' => new DateTime('2020-01-01 00:00:00')
        ]);

        $this->assertInstanceOf(Post::class, $post);
    });