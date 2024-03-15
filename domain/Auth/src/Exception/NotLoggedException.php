<?php
    namespace Domain\Auth\Exception;

    use Exception;

    class NotLoggedException extends Exception
    {
        public function __construct(string $message)
        {
            parent::__construct($message);
        }
    }