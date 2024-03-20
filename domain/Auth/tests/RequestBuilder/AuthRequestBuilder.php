<?php

namespace Domain\Auth\Tests\RequestBuilder;

use Domain\Auth\UseCase\AuthRequest;

class AuthRequestBuilder
{
    private AuthRequest $authRequest;

    public function __construct()
    {
        $this->authRequest = new AuthRequest();
    }

    public function withEmail(string $email): AuthRequestBuilder
    {
        $this->authRequest->email = $email;
        return $this;
    }

    public function withPassword(string $password): AuthRequestBuilder
    {
        $this->authRequest->password = $password;
        return $this;
    }

    public function isPosted(bool $isPosted): AuthRequestBuilder
    {
        $this->authRequest->isPosted = $isPosted;
        return $this;
    }

    public function build(): AuthRequest
    {
        return $this->authRequest;
    }

    public static function anAuthRequest(): AuthRequestBuilder
    {
        return new AuthRequestBuilder();
    }
}
