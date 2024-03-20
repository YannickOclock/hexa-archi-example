<?php

namespace Domain\App\Error;

class Notification
{
    private array $errors = [];

    public function addError(string $fieldName, string $error): static
    {
        $this->errors[] = new Error($fieldName, $error);

        return $this;
    }

    /**
     * @return Error[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasError(): bool
    {
        return count($this->errors) > 0;
    }

    public function hasErrorFor(string $fieldName): bool
    {
        foreach ($this->errors as $error) {
            if ($error->fieldName() === $fieldName) {
                return true;
            }
        }

        return false;
    }

    public function getErrorsFor(string $fieldName): array
    {
        $errors = [];
        foreach ($this->errors as $error) {
            if ($error->fieldName() === $fieldName) {
                $errors[] = $error;
            }
        }

        return $errors;
    }
}
