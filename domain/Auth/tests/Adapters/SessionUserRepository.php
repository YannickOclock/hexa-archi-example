<?php
    namespace Domain\Auth\Tests\Adapters;

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
            $_SESSION['user'] = $sessionUser->getEmail();
        }

        public function getUser(): ?SessionUser
        {
            if (isset($_SESSION['user'])) {
                return new SessionUser($_SESSION['user']);
            }
            return null;
        }

        public function isLogged(): bool
        {
            return false;
        }

        public function isAuthor(): bool
        {
            return false;
        }

        public function logout(): void
        {
            unset($_SESSION['user']);
        }
    }