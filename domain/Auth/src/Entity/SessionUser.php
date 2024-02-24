<?php
    namespace Domain\Auth\Entity;

    class SessionUser
    {
        public function __construct(
            private string $email,
            private array $roles = []
        ) {
        }

        public function getEmail(): string
        {
            return $this->email;
        }

        public function getRoles(): array
        {
            return $this->roles;
        }

        public function hasRole(string $role): bool
        {
            return in_array($role, $this->roles);
        }
    }