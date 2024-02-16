<?php

use Domain\Auth\Entity\User;
use Domain\Auth\Port\UserRepositoryInterface;
use Domain\Auth\UseCase\AuthUser;
use Domain\Auth\Exception\InvalidAuthPostDataException;
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
]);

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