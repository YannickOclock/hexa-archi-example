<?php
    namespace Domain\Auth\Exception;

    use Exception;

    class NotLoggedException extends Exception
    {
        private function __construct(string $message)
        {
            parent::__construct($message);
        }
    }