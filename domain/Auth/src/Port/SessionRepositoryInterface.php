<?php

namespace Domain\Auth\Port;

use Domain\Auth\Entity\SessionUser;

interface SessionRepositoryInterface
{
    public function saveUser(SessionUser $user): void;
    public function getUser(): ?SessionUser;
    public function isLogged(): bool;
    public function isAuthor(): bool;
    public function isPublisher(): bool;
    public function logout(): void;
}
