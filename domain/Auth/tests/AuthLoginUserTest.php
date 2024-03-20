<?php

namespace Domain\Auth\Tests;

use Domain\Auth\Tests\Adapters\InMemorySessionUserRepository;
use Domain\Auth\Tests\Mock\UserMock;
use Domain\Auth\Tests\RequestBuilder\LoginRequestBuilder;
use Domain\Auth\UseCase\Login\LoginPresenter;
use Domain\Auth\UseCase\Login\LoginResponse;
use Domain\Auth\UseCase\Login\LoginUser;
use PHPUnit\Framework\TestCase;

class AuthLoginUserTest extends TestCase implements LoginPresenter
{
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
    public function testShouldAuthenticateUserWithoutAnyEncryption()
    {
        $userRepository = UserMock::mockUserRepository();
        $useCase = new LoginUser($userRepository, $this->sessionRepository);

        $authRequest = LoginRequestBuilder::anAuthRequest()
            ->withEmail('john@doe.fr')
            ->withPassword('password')
            ->isPosted(true)
            ->build();

        $useCase->execute($authRequest, $this);
        $this->assertTrue($this->response->isAuthenticated());
    }

    public function testShouldVerifySessionRolesWithSuccessAuthUser()
    {
        $userRepository = UserMock::mockUserRepository("john@doe.fr", "password", ["publisher"]);
        $useCase = new LoginUser($userRepository, $this->sessionRepository);

        $authRequest = LoginRequestBuilder::anAuthRequest()
            ->withEmail('john@doe.fr')
            ->withPassword('password')
            ->isPosted(true)
            ->build();

        $useCase->execute($authRequest, $this);
        $this->assertTrue($this->sessionRepository->isLogged());
        $this->assertTrue($this->sessionRepository->isPublisher());
    }

    public function testShouldNotifyBadRequestIfPasswordTooShort()
    {
        $userRepository = UserMock::mockUserRepository();
        $useCase = new LoginUser($userRepository, $this->sessionRepository);

        $authRequest = LoginRequestBuilder::anAuthRequest()
            ->withEmail('john@doe.fr')
            ->withPassword('pass')
            ->isPosted(true)
            ->build();

        $useCase->execute($authRequest, $this);
        $this->assertFalse($this->response->isAuthenticated());
        $this->assertNotEmpty($this->response->notification()->getErrors());
        $this->assertCount(1, $this->response->notification()->getErrorsFor('password'));
    }

    public function testShouldNotifyBadRequestIfEmailIsNotValid()
    {
        $userRepository = UserMock::mockUserRepository();
        $useCase = new LoginUser($userRepository, $this->sessionRepository);

        $authRequest = LoginRequestBuilder::anAuthRequest()
            ->withEmail('john@doe')
            ->withPassword('password')
            ->isPosted(true)
            ->build();

        $useCase->execute($authRequest, $this);
        $this->assertFalse($this->response->isAuthenticated());
        $this->assertNotEmpty($this->response->notification()->getErrors());
    }

    public function testShouldNotifyBadRequestIfUserNotFound()
    {
        $userRepository = UserMock::mockUserRepositoryNotFound();
        $useCase = new LoginUser($userRepository, $this->sessionRepository);

        $authRequest = LoginRequestBuilder::anAuthRequest()
            ->withEmail('john@doe.fr')
            ->withPassword('password')
            ->isPosted(true)
            ->build();

        $useCase->execute($authRequest, $this);
        $this->assertFalse($this->response->isAuthenticated());
        $this->assertNotEmpty($this->response->notification()->getErrors());
    }
}
