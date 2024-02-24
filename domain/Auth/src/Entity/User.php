<?php

namespace Domain\Auth\Entity;

class User
{
    public function __construct(
        private string $email,
        private string $password,
        private array $roles = []
    ) {
    }

    public function getEmail(): string
    {
        return $this->email;
    }
    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }
}