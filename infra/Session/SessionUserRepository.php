<?php

namespace App\Session;

use Domain\Auth\Entity\SessionUser;
use Domain\Auth\Port\SessionRepositoryInterface;

class SessionUserRepository implements SessionRepositoryInterface
{
    public function __construct()
    {
        session_start();
    }
    public function saveUser(SessionUser $sessionUser): void
    {
        $_SESSION['user'] = $sessionUser;
    }

    public function getUser(): ?SessionUser
    {
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        }
        return null;
    }

    public function isLogged(): bool
    {
        if (isset($_SESSION['user'])) {
            return true;
        }
        return false;
    }

    public function isAuthor(): bool
    {
        if (isset($_SESSION['user'])) {
            return $_SESSION['user']->hasRole('author');
        }
        return false;
    }

    public function isPublisher(): bool
    {
        if (isset($_SESSION['user'])) {
            return $_SESSION['user']->hasRole('publisher');
        }
        return false;
    }

    public function logout(): void
    {
        unset($_SESSION['user']);
    }
}
