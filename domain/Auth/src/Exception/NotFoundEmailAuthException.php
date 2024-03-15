<?php
    namespace Domain\Auth\Exception;

    final class NotFoundEmailAuthException extends \Exception
    {
        private array $errors;
        public function __construct(string $message)
        {
            parent::__construct($message);
        }

        public static function withMessage(string $message): self
        {
            return new self($message);
        }

        public function getErrors(): array
        {
            $errors = [];
            $errors['global'] = [$this->getMessage()];
            return $errors;
        }
    }