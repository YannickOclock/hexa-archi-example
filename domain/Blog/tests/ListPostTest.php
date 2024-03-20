<?php

namespace Domain\Blog\Tests;

use Domain\Blog\Entity\Post;
use Domain\Blog\Tests\Adapters\InMemoryPostRepository;
use Domain\Blog\Tests\Mock\SessionUserRepositoryMock;
use Domain\Blog\UseCase\ListPost\ListPost;
use Domain\Blog\UseCase\ListPost\ListPostPresenter;
use Domain\Blog\UseCase\ListPost\ListPostResponse;
use PHPUnit\Framework\TestCase;

class ListPostTest extends TestCase implements ListPostPresenter
{
    private InMemoryPostRepository $postRepository;
    private ListPostResponse $response;

    public function present(ListPostResponse $response): void
    {
        $this->response = $response;
    }

    public function setUp(): void
    {
        $this->postRepository = new InMemoryPostRepository();
        $post = new Post();
        $post->title = 'Title';
        $post->content = 'Content';
        $this->postRepository->save($post);
    }

    public function testListPost()
    {
        $sessionUserRepository = SessionUserRepositoryMock::mockLoginAsAuthor();
        $listPost = new ListPost($this->postRepository, $sessionUserRepository);
        $listPost->execute($this);
        $this->assertGreaterThan(0, count($this->response->posts()));
    }
}
