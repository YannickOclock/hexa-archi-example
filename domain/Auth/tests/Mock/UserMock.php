<?php

namespace Domain\Auth\Tests\Mock;

use Domain\Auth\Entity\User;
use Domain\Auth\Port\UserRepositoryInterface;
use Mockery;

class UserMock extends User
{
    public static function mockUserRepository(string $fakeEmail = "john@doe.fr", string $fakePassword = "password", array $roles = ["author"]): UserRepositoryInterface
    {
        $userRepository = Mockery::mock(UserRepositoryInterface::class);
        $userRepository->shouldReceive('findByEmail')
            ->with($fakeEmail)
            ->andReturn(
                new User(
                    $fakeEmail,
                    $fakePassword,
                    $roles
                )
            );
        return $userRepository;
    }
    public static function mockUserRepositoryNotFound(string $fakeEmail = "john@doe.fr"): UserRepositoryInterface
    {
        $userRepository = Mockery::mock(UserRepositoryInterface::class);
        $userRepository->shouldReceive('findByEmail')
            ->with($fakeEmail)
            ->andReturn(null);
        return $userRepository;
    }
}
