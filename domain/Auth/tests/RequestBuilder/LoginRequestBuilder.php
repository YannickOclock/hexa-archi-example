<?php

namespace Domain\Auth\Tests\RequestBuilder;

use Domain\Auth\UseCase\Login\LoginRequest;

class LoginRequestBuilder
{
    private LoginRequest $loginRequest;

    public function __construct()
    {
        $this->loginRequest = new LoginRequest();
    }

    public function withEmail(string $email): LoginRequestBuilder
    {
        $this->loginRequest->email = $email;
        return $this;
    }

    public function withPassword(string $password): LoginRequestBuilder
    {
        $this->loginRequest->password = $password;
        return $this;
    }

    public function isPosted(bool $isPosted): LoginRequestBuilder
    {
        $this->loginRequest->isPosted = $isPosted;
        return $this;
    }

    public function build(): LoginRequest
    {
        return $this->loginRequest;
    }

    public static function anAuthRequest(): LoginRequestBuilder
    {
        return new LoginRequestBuilder();
    }
}
