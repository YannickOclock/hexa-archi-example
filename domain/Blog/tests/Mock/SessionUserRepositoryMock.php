<?php

namespace Domain\Blog\Tests\Mock;

use Domain\Auth\Entity\SessionUser;
use Domain\Auth\Port\SessionRepositoryInterface;
use Mockery;

class SessionUserRepositoryMock
{
    public static function mockLoginAsAuthor(string $fakeEmail = "author@test.fr"): SessionRepositoryInterface
    {
        $sessionRepository = Mockery::mock(SessionRepositoryInterface::class);
        $sessionRepository->shouldReceive('isLogged')
            ->andReturn(true);
        $sessionRepository->shouldReceive('isAuthor')
            ->andReturn(true);
        $sessionRepository->shouldReceive('isPublisher')
            ->andReturn(false);
        $sessionRepository->shouldReceive('getUser')
            ->andReturn(
                new SessionUser(
                    $fakeEmail,
                    ["author"]
                )
            );
        return $sessionRepository;
    }

    public static function mockLoginAsPublisher(string $fakeEmail = "publisher@test.fr"): SessionRepositoryInterface
    {
        $sessionRepository = Mockery::mock(SessionRepositoryInterface::class);
        $sessionRepository->shouldReceive('isLogged')
            ->andReturn(true);
        $sessionRepository->shouldReceive('isAuthor')
            ->andReturn(true);
        $sessionRepository->shouldReceive('isPublisher')
            ->andReturn(true);
        $sessionRepository->shouldReceive('getUser')
            ->andReturn(
                new SessionUser(
                    $fakeEmail,
                    ["publisher"]
                )
            );
        return $sessionRepository;
    }

    public static function MockNoUser(): SessionRepositoryInterface
    {
        $sessionRepository = Mockery::mock(SessionRepositoryInterface::class);
        $sessionRepository->shouldReceive('isLogged')
            ->andReturn(false);
        $sessionRepository->shouldReceive('isAuthor')
            ->andReturn(false);
        $sessionRepository->shouldReceive('isPublisher')
            ->andReturn(false);
        $sessionRepository->shouldReceive('getUser')
            ->andReturn(null);
        return $sessionRepository;
    }
}
