<?php
    namespace Domain\Auth\Exception;

    use Exception;

    class IsNotAPublisherException extends Exception
    {
        public function __construct(string $message)
        {
            parent::__construct($message);
        }
    }