<?php

namespace Domain\Auth\UseCase\Register;

interface RegisterPresenter
{
    public function present(RegisterResponse $response): void;
}
