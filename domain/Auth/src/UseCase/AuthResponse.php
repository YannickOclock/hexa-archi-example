<?php

namespace Domain\Auth\UseCase;

use Domain\App\Error\Notification;

class AuthResponse
{
    private Notification $notification;
    private bool $authenticated = false;

    public function __construct(
    ) {
        $this->notification = new Notification();
    }

    public function setAuthenticated(): void
    {
        $this->authenticated = true;
    }

    public function isAuthenticated(): bool
    {
        return $this->authenticated;
    }

    public function addError(string $fieldName, string $error)
    {
        $this->notification->addError($fieldName, $error);
    }

    public function notification(): Notification
    {
        return $this->notification;
    }
}
