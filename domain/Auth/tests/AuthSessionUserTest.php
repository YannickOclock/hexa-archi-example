<?php

namespace Domain\Auth\Tests;

use Domain\Auth\Tests\Adapters\InMemorySessionUserRepository;
use Domain\Auth\Tests\Mock\UserMock;
use Domain\Auth\Tests\RequestBuilder\LoginRequestBuilder;
use Domain\Auth\UseCase\Login\LoginPresenter;
use Domain\Auth\UseCase\Login\LoginRequest;
use Domain\Auth\UseCase\Login\LoginResponse;
use Domain\Auth\UseCase\Login\LoginUser;
use PHPUnit\Framework\TestCase;

class AuthSessionUserTest extends TestCase implements LoginPresenter {
    private LoginResponse $response;
    private InMemorySessionUserRepository $sessionRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->sessionRepository = new InMemorySessionUserRepository();
    }

    public function present(LoginResponse $response): void
    {
        $this->response = $response;
    }

    // tests
    //should save email address in a session user
    public function testShouldSaveEmailAddressInASessionUser()
    {
        $userRepository = UserMock::mockUserRepository();
        $useCase = new LoginUser($userRepository, $this->sessionRepository);

        $authRequest = LoginRequestBuilder::anAuthRequest()
            ->withEmail('john@doe.fr')
            ->withPassword('password')
            ->isPosted(true)
            ->build();
        
        $useCase->execute($authRequest, $this);
        $this->assertEquals('john@doe.fr', $this->sessionRepository->getUser()->getEmail());
        $this->assertTrue($this->sessionRepository->isLogged());
        $this->assertTrue($this->sessionRepository->isAuthor());
    }
}