<?php

namespace Domain\Auth\Tests;

use Domain\Auth\Tests\Adapters\InMemorySessionUserRepository;
use Domain\Auth\Tests\Mock\UserMock;
use Domain\Auth\UseCase\AuthUser;

function simulateUser($pUserData) {
    $userRepository = UserMock::mockUserRepository();
    $sessionRepository = new InMemorySessionUserRepository();
    $useCase = new AuthUser($userRepository, $sessionRepository);
    $isAuthenticate = $useCase->execute($pUserData);
    return $sessionRepository;
}

it("should save email address in a session user", function ($userData) {
    $sessionRepository = simulateUser($userData);
    $this->assertEquals($userData['email'], $sessionRepository->getUser()->getEmail());
})->with([
    [['email' => 'john@doe.fr', 'password' => 'password']]
]);

it("should be logged after authenticate", function ($userData) {
    $sessionRepository = simulateUser($userData);
    $this->assertEquals(true, $sessionRepository->isLogged());
})->with([
    [['email' => 'john@doe.fr', 'password' => 'password']]
]);

it("should be an author after authenticate", function ($userData) {
    $sessionRepository = simulateUser($userData);
    $this->assertEquals(true, $sessionRepository->isAuthor());
})->with([
    [['email' => 'john@doe.fr', 'password' => 'password']]
]);

