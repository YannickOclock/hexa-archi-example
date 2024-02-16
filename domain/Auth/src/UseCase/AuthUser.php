<?php

namespace Domain\Auth\UseCase;

use Assert\LazyAssertionException;
use Domain\Auth\Entity\User;
use Domain\Auth\Exception\BadCredentialsAuthException;
use Domain\Auth\Port\UserRepositoryInterface;
use Domain\Auth\Exception\InvalidAuthPostDataException;
use Domain\Auth\Exception\NotFoundEmailAuthException;

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
                throw NotFoundEmailAuthException::withMessage('Votre email n\'est pas enregistré dans notre base de données. Veuillez vous inscrire.');
            }
            // si l'utilisateur est trouvé, je compare les mots de passe
            if($userFrom->getPassword() !== $userData->getPassword()) {
                throw BadCredentialsAuthException::withMessage('Email ou mot de passe incorrect');
            }
            return true;
        } catch (LazyAssertionException $e) {
            throw InvalidAuthPostDataException::withErrors($e->getErrorExceptions());
        }
    }

    protected function validate(User $user): void
    {
        lazy()
            ->tryAll()
            ->that($user->getEmail(), 'email')
                ->notBlank()
                ->email()
            ->that($user->getPassword(), 'password')
                ->notBlank()
                ->minLength(8)
            ->verifyNow()    
        ;
    }
}