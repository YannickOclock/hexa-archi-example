<?php

namespace Domain\Auth\UseCase\Register;

class RegisterRequest
{
    public string $email = '';
    public string $password = '';
    public string $passwordConfirmation = '';
    public bool $isPosted = false;
}
