<?php
    namespace Domain\Blog\Exception;

    final class InvalidPostDataException extends \Exception
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
            if(isset($this->errors)) {
                foreach($this->errors as $error) {
                    if($error instanceof \Assert\AssertionFailedException) {
                        if(!isset($errors[$error->getPropertyPath()])) {
                            $errors[$error->getPropertyPath()] = [];
                        }
                        $errors[$error->getPropertyPath()][] = $error->getMessage();
                    }
                }
            }
            if($this->message) {
                $errors['global'][] = $this->message;
            }
            return $errors;
        }
    }