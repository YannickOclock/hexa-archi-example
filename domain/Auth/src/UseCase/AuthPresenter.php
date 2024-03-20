<?php

namespace Domain\Auth\UseCase;

interface AuthPresenter
{
    public function present(AuthResponse $response): void;
}
