<?php

namespace Domain\Auth\UseCase\Register;

use Domain\App\Error\Notification;
use Domain\Auth\Entity\User;

class RegisterResponse
{
    private Notification $notification;
    private ?User $user = null;

    public function __construct(
    ) {
        $this->notification = new Notification();
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function user(): ?User
    {
        return $this->user;
    }

    public function addError(string $fieldName, string $error): void
    {
        $this->notification->addError($fieldName, $error);
    }

    public function notification(): Notification
    {
        return $this->notification;
    }
}