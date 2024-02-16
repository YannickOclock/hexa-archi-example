<?php

namespace Domain\Auth\UseCase;

use Assert\LazyAssertionException;
use Domain\Auth\Entity\User;
use Domain\Auth\Port\UserRepositoryInterface;
use Domain\Auth\Exception\InvalidAuthPostDataException;

use function Assert\lazy;

class AuthUser
{
    public function __construct(private UserRepositoryInterface $userRepository) {
    }

    public function execute(array $data): bool
    {
        try {
            // valider les données du formulaire en premier
            $userData = new User($data['email'] ?? '', $data['password'] ?? '');
            $this->validate($userData);

            // si les données du formulaire sont OK, je chercher l'utilisateur en base
            $userFrom = $this->userRepository->findByEmail($data['email']);
            if ($userFrom === null) {
                return true;
            }

            return $userFrom->getPassword() === $userData->getPassword();
        } catch (LazyAssertionException $e) {
            throw InvalidAuthPostDataException::withMessage($e->getMessage());
        }
    }

    protected function validate(User $user): void
    {
        lazy()
            ->that($user->getEmail())
                ->notBlank()
                ->email()
            ->that($user->getPassword())
                ->notBlank()
                ->minLength(8)
            ->verifyNow()    
        ;
    }
}