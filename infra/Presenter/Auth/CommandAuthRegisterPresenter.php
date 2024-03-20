<?php

namespace App\Presenter\Auth;

use Domain\Auth\UseCase\Register\RegisterPresenter;
use Domain\Auth\UseCase\Register\RegisterResponse;

class CommandAuthRegisterPresenter implements RegisterPresenter
{
    private string $viewmodel;

    public function __construct(
    ) {
    }

    public function present(RegisterResponse $response): void
    {
        $data = [
            'notification' => $response->notification()
        ];

        if ($response->user()) {
            $this->viewmodel = "User created!".PHP_EOL;
        } else {
            $this->viewmodel = "User not created!".PHP_EOL;
            // parse data to display error messages
            $this->viewmodel .= "Errors:".PHP_EOL;
            foreach ($response->notification()->getErrors() as $key => $error) {
                $this->viewmodel .= " - " . $key . " : " . $error->fieldName() . " : " . $error->message() . PHP_EOL;
            }
        }
    }

    public function viewModel(): string
    {
        return $this->viewmodel;
    }
}
