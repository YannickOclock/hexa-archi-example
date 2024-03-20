<?php

namespace Domain\Auth\Tests\RequestBuilder;

use Domain\Auth\UseCase\Register\RegisterRequest;

class RegisterRequestBuilder
{
    private RegisterRequest $registerRequest;

    public function __construct()
    {
        $this->registerRequest = new RegisterRequest();
    }

    public function withEmail(string $email): RegisterRequestBuilder
    {
        $this->registerRequest->email = $email;
        return $this;
    }

    public function withPassword(string $password): RegisterRequestBuilder
    {
        $this->registerRequest->password = $password;
        return $this;
    }

    public function withPasswordConfirmation(string $passwordConfirmation): RegisterRequestBuilder
    {
        $this->registerRequest->passwordConfirmation = $passwordConfirmation;
        return $this;
    }

    public function isPosted(bool $isPosted): RegisterRequestBuilder
    {
        $this->registerRequest->isPosted = $isPosted;
        return $this;
    }

    public function build(): RegisterRequest
    {
        return $this->registerRequest;
    }

    public static function anAuthRequest(): RegisterRequestBuilder
    {
        return new RegisterRequestBuilder();
    }
}
