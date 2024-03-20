<?php

// script qui va créer un utilisateur

require_once __DIR__ . '/vendor/autoload.php';

use Domain\Auth\UseCase\Register\RegisterUser;
use App\Pdo\PdoUserRepository;
use Domain\Auth\UseCase\Register\RegisterRequest;
use App\Presenter\Auth\CommandAuthRegisterPresenter;
use Symfony\Component\Dotenv\Dotenv;

// on charge les variables d'environnement
$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');

// on demande à l'utilisateur les informations

$email = readline('Email: ');
$password = readline('Password: ');
$passwordConfirmation = readline('Password confirmation: ');

// on crée une instance de RegisterUser

$registerUser = new RegisterUser(new PdoUserRepository());
$registerRequest = new RegisterRequest();
$registerRequest->email = $email;
$registerRequest->password = $password;
$registerRequest->passwordConfirmation = $passwordConfirmation;
$registerRequest->isPosted = true;

$registerPresenter = new CommandAuthRegisterPresenter();

$registerUser->execute($registerRequest, $registerPresenter);
echo $registerPresenter->viewModel();