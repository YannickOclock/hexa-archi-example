<?php
    namespace Domain\Auth\Exception;

    final class InvalidAuthPostDataException extends \Exception
    {
        public static function withMessage(string $message): self
        {
            return new self($message);
        }
    }