<?php
    namespace Domain\Auth\Tests\Adapters;

    use Domain\Auth\Entity\SessionUser;
    use Domain\Auth\Port\SessionRepositoryInterface;

    class InMemorySessionUserRepository implements SessionRepositoryInterface
    {
        private ?SessionUser $sessionUser = null;

        public function saveUser(SessionUser $sessionUser): void
        {
            $this->sessionUser = $sessionUser;
        }

        public function getUser(): ?SessionUser
        {
            return $this->sessionUser;
        }

        public function isLogged(): bool
        {
            // Implement the logic for checking if the user is logged in.
            return !empty($this->sessionUser);
        }

        public function isAuthor(): bool
        {
            // Implement the logic for checking if the user is an author.
            return $this->sessionUser->hasRole('author');
        }

        public function isPublisher(): bool
        {
            // Implement the logic for checking if the user is a publisher.
            return $this->sessionUser->hasRole('publisher');
        }

        public function logout(): void
        {
            // Implement the logic for logging out the user.
        }
    }
    