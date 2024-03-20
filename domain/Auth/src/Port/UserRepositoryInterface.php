<?php

namespace Domain\Auth\Port;

use Domain\Auth\Entity\User;

interface UserRepositoryInterface
{
    public function findByEmail(string $email): ?User;
    public function save(User $user): bool;
}
