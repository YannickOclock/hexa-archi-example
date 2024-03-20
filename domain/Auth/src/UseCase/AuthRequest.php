<?php

namespace Domain\Auth\UseCase;

class AuthRequest
{
    public string $email;
    public string $password;
    public bool $isPosted = false;
}
