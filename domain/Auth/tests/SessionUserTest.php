<?php

namespace Domain\Auth\Tests;

use Domain\Auth\Tests\Adapters\InMemorySessionUserRepository;
use Domain\Auth\Tests\Mock\UserMock;
use Domain\Auth\Tests\RequestBuilder\AuthRequestBuilder;
use Domain\Auth\UseCase\AuthPresenter;
use Domain\Auth\UseCase\AuthResponse;
use Domain\Auth\UseCase\AuthUser;
use PHPUnit\Framework\TestCase;

class AuthUserTest extends TestCase implements AuthPresenter {
    private AuthResponse $response;
    private InMemorySessionUserRepository $sessionRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->sessionRepository = new InMemorySessionUserRepository();
    }

    public function present(AuthResponse $response): void
    {
        $this->response = $response;
    }

    // tests
    //should save email address in a session user
    public function testShouldSaveEmailAddressInASessionUser()
    {
        $userRepository = UserMock::mockUserRepository();
        $useCase = new AuthUser($userRepository, $this->sessionRepository);

        $authRequest = AuthRequestBuilder::anAuthRequest()
            ->withEmail('john@doe.fr')
            ->withPassword('password')
            ->isPosted(true)
            ->build();
        
        $useCase->execute($authRequest, $this);
        $this->assertEquals('john@doe.fr', $this->sessionRepository->getUser()->getEmail());
        $this->assertEquals(true, $this->sessionRepository->isLogged());
        $this->assertEquals(true, $this->sessionRepository->isAuthor());
    }
}