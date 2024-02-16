<?php

use Domain\Auth\Entity\User;
use Domain\Auth\Exception\BadCredentialsAuthException;
use Domain\Auth\Port\UserRepositoryInterface;
use Domain\Auth\UseCase\AuthUser;
use Domain\Auth\Exception\InvalidAuthPostDataException;
use Domain\Auth\Exception\NotFoundEmailAuthException;
use Mockery;

function mockUserRepository(string $fakeEmail = "john@doe.fr", string $fakePassword = "password"): UserRepositoryInterface
{
    $userRepository = Mockery::mock(UserRepositoryInterface::class);
    $userRepository->shouldReceive('findByEmail')
        ->with($fakeEmail)
        ->andReturn(
            new User(
                $fakeEmail,
                $fakePassword
            )
        );
    return $userRepository;
}
function mockUserRepositoryNotFound(string $fakeEmail = "luc@doe.fr"): UserRepositoryInterface
{
    $userRepository = Mockery::mock(UserRepositoryInterface::class);
    $userRepository->shouldReceive('findByEmail')
        ->with($fakeEmail)
        ->andReturn(null);
    return $userRepository;
}

it("should authenticate user (without any encryption)", function ($userData) {
    $userRepository = mockUserRepository();
    $useCase = new AuthUser($userRepository);
    $isAuthenticate = $useCase->execute($userData);
    $this->assertEquals(true, $isAuthenticate);
})->with([
    [['email' => 'john@doe.fr', 'password' => 'password']]
]);

it("should not authenticate user (without any encryption)", function ($userData) {
    $userRepository = mockUserRepository();
    $useCase = new AuthUser($userRepository);
    $isAuthenticate = $useCase->execute($userData);
    $this->assertEquals(false, $isAuthenticate);
})->with([
    [['email' => 'john@doe.fr', 'password' => 'badPassword']]
])->expectException(BadCredentialsAuthException::class);

it("should throw a NotFoundEmailAuthException if user not exists in Repository", function ($userData) {
    $userRepository = mockUserRepositoryNotFound();
    $useCase = new AuthUser($userRepository);
    $isAuthenticate = $useCase->execute($userData);
})->with([
    [['email' => 'luc@doe.fr', 'password' => 'password']]
])->expectException(NotFoundEmailAuthException::class);

it("should throw an InvalidAuthPostDataException if the data auth form are not valid (passwordTooShort)", function ($userData) {
    $userRepository = mockUserRepository();
    $useCase = new AuthUser($userRepository);
    $isAuthenticate = $useCase->execute($userData);
})->with([
    [['email' => 'john@doe.fr', 'password' => 'pass']],
    [['email' => 'john', 'password' => 'password']],
    [['email' => '', 'password' => '']],
    [[]]
])->expectException(InvalidAuthPostDataException::class);