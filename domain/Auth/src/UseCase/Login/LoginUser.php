<?php

namespace Domain\Auth\UseCase\Login;

use Assert\LazyAssertionException;
use Domain\Auth\Entity\SessionUser;
use Domain\Auth\Entity\User;
use Domain\Auth\Port\UserRepositoryInterface;
use Domain\Auth\Port\SessionRepositoryInterface;

use function Assert\lazy;

readonly class LoginUser
{
    public function __construct(
        private UserRepositoryInterface    $userRepository,
        private SessionRepositoryInterface $sessionRepository
    ) {
    }

    public function execute(LoginRequest $request, LoginPresenter $presenter): void
    {
        $response = new LoginResponse();

        if($request->isPosted) {
            $isValid = $this->login($request, $response);
            if($isValid) {
                $response->setAuthenticated();
            }
        }

        $presenter->present($response);
    }

    protected function login(LoginRequest $request, LoginResponse $response): bool
    {
        // valider les données du formulaire en premier
        $isValid = $this->validateRequest($request, $response);

        if($isValid) {
            // Créer le User
            $userData = new User();
            $userData->login($request->email ?? '', $request->password ?? '');

            // si les données du formulaire sont OK, je chercher l'utilisateur en base
            $userFrom = $this->userRepository->findByEmail($request->email);
            if ($userFrom === null) {
                $response->addError('global', 'Votre email n\'est pas enregistré dans notre base de données. Veuillez vous inscrire.');
                return false;
            }

            // si l'utilisateur est trouvé, je compare les mots de passe
            if($userFrom->getPassword() !== $userData->getPassword()) {
                $response->addError('global', 'Le mot de passe est incorrect');
                return false;
            }

            // si le mot de passe est OK, je peux sauvegarder l'utilisateur en session
            $sessionUser = new SessionUser($userData->getEmail(), $userFrom->getRoles());
            $this->sessionRepository->saveUser($sessionUser);

            return true;
        }
        return false;
    }

    public function logout(): void
    {
        $this->sessionRepository->logout();
    }

    protected function validateRequest(LoginRequest $request, LoginResponse $response): bool
    {
        try {
            lazy()
                ->tryAll()
                ->that($request->email, 'email')
                    ->notBlank("L'email ne doit pas être vide")
                    ->email("L'email n'est pas valide")
                ->that($request->password, 'password')
                    ->notBlank("Le mot de passe ne doit pas être vide")
                    ->minLength(8, "Le mot de passe doit faire au moins 8 caractères")
                ->verifyNow()
            ;
            return true;
        } catch (LazyAssertionException $e) {
            foreach($e->getErrorExceptions() as $error) {
                $response->addError($error->getPropertyPath(), $error->getMessage());
            }
            return false;
        }
    }
}
