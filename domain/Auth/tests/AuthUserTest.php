<?php

use Domain\Auth\Exception\BadCredentialsAuthException;
use Domain\Auth\Tests\Adapters\InMemorySessionUserRepository;
use Domain\Auth\Tests\Mock\UserMock;
use Domain\Auth\UseCase\AuthUser;
use Domain\Auth\Exception\InvalidAuthPostDataException;
use Domain\Auth\Exception\NotFoundEmailAuthException;

it("should authenticate user (without any encryption)", function ($userData) {
    $userRepository = UserMock::mockUserRepository();
    $sessionRepository = new InMemorySessionUserRepository();
    $useCase = new AuthUser($userRepository, $sessionRepository);
    $isAuthenticate = $useCase->execute($userData);
    $this->assertEquals(true, $isAuthenticate);
})->with([
    [['email' => 'john@doe.fr', 'password' => 'password']]
]);

it("should verify session Roles with success auth user", function ($userData) {
    $userRepository = UserMock::mockUserRepository("john@doe.fr", "password", ["publisher"]);
    $sessionRepository = new InMemorySessionUserRepository();
    $useCase = new AuthUser($userRepository, $sessionRepository);
    $isAuthenticate = $useCase->execute($userData);
    $this->assertEquals(true, $sessionRepository->isLogged());
    $this->assertEquals(true, $sessionRepository->isPublisher());
})->with([
    [['email' => 'john@doe.fr', 'password' => 'password']]
]);

it("should not authenticate user (without any encryption)", function ($userData) {
    $userRepository = UserMock::mockUserRepository();
    $sessionRepository = new InMemorySessionUserRepository();
    $useCase = new AuthUser($userRepository, $sessionRepository);
    $isAuthenticate = $useCase->execute($userData);
    $this->assertEquals(false, $isAuthenticate);
})->with([
    [['email' => 'john@doe.fr', 'password' => 'badPassword']]
])->expectException(BadCredentialsAuthException::class);

it("should throw a NotFoundEmailAuthException if user not exists in Repository", function ($userData) {
    $userRepository = UserMock::mockUserRepositoryNotFound();
    $sessionRepository = new InMemorySessionUserRepository();
    $useCase = new AuthUser($userRepository, $sessionRepository);
    $isAuthenticate = $useCase->execute($userData);
})->with([
    [['email' => 'luc@doe.fr', 'password' => 'password']]
])->expectException(NotFoundEmailAuthException::class);

it("should throw an InvalidAuthPostDataException if the data auth form are not valid (passwordTooShort)", function ($userData) {
    $userRepository = UserMock::mockUserRepository();
    $sessionRepository = new InMemorySessionUserRepository();
    $useCase = new AuthUser($userRepository, $sessionRepository);
    $isAuthenticate = $useCase->execute($userData);
})->with([
    [['email' => 'john@doe.fr', 'password' => 'pass']],
    [['email' => 'john', 'password' => 'password']],
    [['email' => '', 'password' => '']],
    [[]]
])->expectException(InvalidAuthPostDataException::class);