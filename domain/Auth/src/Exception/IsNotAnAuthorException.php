<?php
    namespace Domain\Auth\Exception;

    use Exception;

    class IsNotAnAuthorException extends Exception
    {
        private function __construct(string $message)
        {
            parent::__construct($message);
        }
    }