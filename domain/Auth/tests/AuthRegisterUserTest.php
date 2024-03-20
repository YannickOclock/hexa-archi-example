<?php

namespace Domain\Auth\Tests;

use Domain\Auth\Port\UserRepositoryInterface;
use Domain\Auth\Tests\RequestBuilder\RegisterRequestBuilder;
use Domain\Auth\UseCase\Register\RegisterPresenter;
use Domain\Auth\UseCase\Register\RegisterResponse;
use Domain\Auth\UseCase\Register\RegisterUser;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class AuthRegisterUserTest extends TestCase implements RegisterPresenter
{
    private RegisterResponse $response;

    public function present(RegisterResponse $response): void
    {
        $this->response = $response;
    }

    /**
     * @throws Exception
     */
    public function testRegisterUser()
    {
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository->expects($this->once())
            ->method('save')
            ->willReturn(true);

        $registerUser = new RegisterUser($userRepository);

        $request = RegisterRequestBuilder::anAuthRequest()
            ->withEmail('john@doe.fr')
            ->withPassword('password')
            ->withPasswordConfirmation('password')
            ->isPosted(true)
            ->build();
        
        $registerUser->execute($request, $this);
        $this->assertEquals('john@doe.fr', $this->response->user()->getEmail());
    }

    /**
     * @throws Exception
     */
    public function testRegisterUserWithInvalidData()
    {
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository->expects($this->never())
            ->method('save');

        $registerUser = new RegisterUser($userRepository);

        $request = RegisterRequestBuilder::anAuthRequest()
            ->withEmail('john@doe.fr')
            ->withPassword('password')
            ->withPasswordConfirmation('pass')
            ->isPosted(true)
            ->build();

        $registerUser->execute($request, $this);
        $this->assertNotEmpty($this->response->notification()->getErrorsFor('passwordConfirmation'));
    }
}