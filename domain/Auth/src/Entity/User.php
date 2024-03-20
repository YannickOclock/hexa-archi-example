<?php

namespace Domain\Auth\Entity;

class User
{
    private string $email = '';
    private string $password = '';
    private array $roles = [];
    private ?string $uuid = null;

    // Cette méthode est utilisée par PDO pour créer un objet User
    public function __construct(string $email = '', string $password = '', array $roles = [], ?string $uuid = null)
    {
        $this->email = $email;
        $this->password = $password;
        $this->roles = $roles;
        $this->uuid = $uuid;
    }

    public function register(string $email, string $password, array $roles = []): void
    {
        $this->email = $email;
        $this->password = $password;
        $this->roles = $roles;
        $this->uuid = uniqid();
    }

    public function login(string $email, string $password): void
    {
        $this->email = $email;
        $this->password = $password;
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

    public function getUuid(): ?string
    {
        return $this->uuid;
    }
}
