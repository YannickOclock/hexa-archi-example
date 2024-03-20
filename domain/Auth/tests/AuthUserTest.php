<?php

use Domain\Auth\Tests\Adapters\InMemorySessionUserRepository;
use Domain\Auth\Tests\Mock\UserMock;
use Domain\Auth\UseCase\AuthUser;
use Domain\Auth\Tests\RequestBuilder\AuthRequestBuilder;
use Domain\Auth\UseCase\AuthPresenter;
use Domain\Auth\UseCase\AuthResponse;
use PHPUnit\Framework\TestCase;

class AuthUserTest extends TestCase implements AuthPresenter
{
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
    public function testShouldAuthenticateUserWithoutAnyEncryption()
    {
        $userRepository = UserMock::mockUserRepository();
        $useCase = new AuthUser($userRepository, $this->sessionRepository);

        $authRequest = AuthRequestBuilder::anAuthRequest()
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
        $useCase = new AuthUser($userRepository, $this->sessionRepository);

        $authRequest = AuthRequestBuilder::anAuthRequest()
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
        $useCase = new AuthUser($userRepository, $this->sessionRepository);

        $authRequest = AuthRequestBuilder::anAuthRequest()
            ->withEmail('john@doe.fr')
            ->withPassword('pass')
            ->isPosted(true)
            ->build();

        $useCase->execute($authRequest, $this);
        $this->assertFalse($this->response->isAuthenticated());
        $this->assertNotEmpty($this->response->notification()->getErrors());
        $this->assertEquals(1, count($this->response->notification()->getErrorsFor('password')));
    }

    public function testShouldNotifyBadRequestIfEmailIsNotValid()
    {
        $userRepository = UserMock::mockUserRepository();
        $useCase = new AuthUser($userRepository, $this->sessionRepository);

        $authRequest = AuthRequestBuilder::anAuthRequest()
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
        $useCase = new AuthUser($userRepository, $this->sessionRepository);

        $authRequest = AuthRequestBuilder::anAuthRequest()
            ->withEmail('john@doe.fr')
            ->withPassword('password')
            ->isPosted(true)
            ->build();
        
        $useCase->execute($authRequest, $this);
        $this->assertFalse($this->response->isAuthenticated());
        $this->assertNotEmpty($this->response->notification()->getErrors());
    }
}