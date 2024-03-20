<?php

namespace Domain\Auth\UseCase\Login;

class LoginRequest
{
    public string $email;
    public string $password;
    public bool $isPosted = false;
}
