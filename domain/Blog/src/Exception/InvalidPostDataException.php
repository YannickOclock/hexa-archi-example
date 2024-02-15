<?php
    namespace Domain\Blog\Exception;

    final class InvalidPostDataException extends \Exception
    {
        public static function withMessage(string $message): self
        {
            return new self($message);
        }
    }