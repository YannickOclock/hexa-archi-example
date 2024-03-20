<?php

namespace Domain\Blog\UseCase\ListPost;

interface ListPostPresenter
{
    public function present(ListPostResponse $response): void;
}