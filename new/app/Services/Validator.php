<?php

declare(strict_types=1);

namespace App\Services;

final class Validator
{
    /** @var array<string, string> */
    private array $errors = [];

    public function required(string $field, ?string $value, string $message = 'This field is required.'): self
    {
        if ($value === null || trim($value) === '') {
            $this->errors[$field] = $message;
        }
        return $this;
    }

    public function email(string $field, ?string $value): self
    {
        if ($value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = 'Please enter a valid email address.';
        }
        return $this;
    }

    /** @return array<string, string> */
    public function errors(): array
    {
        return $this->errors;
    }

    public function fails(): bool
    {
        return $this->errors !== [];
    }
}
