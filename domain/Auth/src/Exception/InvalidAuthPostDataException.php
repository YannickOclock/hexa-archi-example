<?php
    namespace Domain\Auth\Exception;

    final class InvalidAuthPostDataException extends \Exception
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

        public static function withErrors(array $errors): self
        {
            $message = 'Invalid data';
            $instance = new self($message);
            $instance->errors = $errors;
            return $instance;
        }

        public function getErrors(): array
        {
            $errors = [];
            foreach($this->errors as $error) {
                if($error instanceof \Assert\AssertionFailedException) {
                    if(!isset($errors[$error->getPropertyPath()])) {
                        $errors[$error->getPropertyPath()] = [];
                    }
                    $errors[$error->getPropertyPath()][] = $error->getMessage();
                }
            }
            return $errors;
        }
    }