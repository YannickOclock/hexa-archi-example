<?php

namespace Domain\Auth\UseCase\Login;

interface LoginPresenter
{
    public function present(LoginResponse $response): void;
}
