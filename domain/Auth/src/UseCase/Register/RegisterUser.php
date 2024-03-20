<?php

namespace Domain\Auth\UseCase\Register;

use Assert\LazyAssertionException;
use Domain\Auth\Entity\User;
use Domain\Auth\Port\UserRepositoryInterface;

use function Assert\lazy;

readonly class RegisterUser
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function execute(RegisterRequest $request, RegisterPresenter $presenter): void
    {
        $response = new RegisterResponse();

        if($request->isPosted) {
            $this->registerUser($request, $response);
        }

        $presenter->present($response);
    }

    private function registerUser(RegisterRequest $request, RegisterResponse $response): void
    {
        // TODO: Check if user is already logged in

        $user = new User();
        $user->register($request->email ?? '', $request->password ?? '', ['author']);

        $isValid = $this->validateRequest($request, $response);

        // TODO: Check if user already exists

        $isValid = $isValid && $this->userRepository->save($user);

        if(!$isValid) {
            $response->addError('global', 'Une erreur est survenue lors de l\'enregistrement de l\'utilisateur');
        } else {
            $response->setUser($user);
        }

    }

    protected function validateRequest(RegisterRequest $request, RegisterResponse $response): bool
    {
        try {
            lazy()
                ->tryAll()
                ->that($request->email, 'email')
                    ->notEmpty("L'email ne doit pas être vide")
                    ->minLength(5, "L'email doit contenir au moins 5 caractères")
                    ->email("L'email n'est pas valide")
                ->that($request->password, 'password')
                    ->notEmpty("Le mot de passe ne doit pas être vide")
                    ->minLength(8, "Le mot de passe doit contenir au moins 8 caractères")
                ->that($request->passwordConfirmation, 'passwordConfirmation')
                    ->notEmpty("La confirmation du mot de passe ne doit pas être vide")
                    ->minLength(8, "La confirmation du mot de passe doit contenir au moins 8 caractères")
                    ->eq($request->password, "La confirmation du mot de passe doit être identique au mot de passe")
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
