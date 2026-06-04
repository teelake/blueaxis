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

    public function email(string $field, ?string $value, string $message = 'Please enter a valid email address.'): self
    {
        $value = trim((string) $value);
        if ($value === '') {
            return $this;
        }
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = $message;
        }
        return $this;
    }

    public function maxLength(string $field, ?string $value, int $max, ?string $message = null): self
    {
        if ($this->hasError($field)) {
            return $this;
        }
        $value = (string) $value;
        if (mb_strlen($value) > $max) {
            $this->errors[$field] = $message ?? "Must be {$max} characters or fewer.";
        }
        return $this;
    }

    public function minLength(string $field, ?string $value, int $min, ?string $message = null): self
    {
        if ($this->hasError($field)) {
            return $this;
        }
        $value = trim((string) $value);
        if ($value !== '' && mb_strlen($value) < $min) {
            $this->errors[$field] = $message ?? "Must be at least {$min} characters.";
        }
        return $this;
    }

    public function max(string $field, mixed $value, float $max, ?string $message = null): self
    {
        if ($this->hasError($field) || $value === null || $value === '') {
            return $this;
        }
        if ((float) $value > $max) {
            $this->errors[$field] = $message ?? "Must be {$max} or less.";
        }
        return $this;
    }

    public function min(string $field, mixed $value, float $min, ?string $message = null): self
    {
        if ($this->hasError($field) || $value === null || $value === '') {
            return $this;
        }
        if ((float) $value < $min) {
            $this->errors[$field] = $message ?? "Must be at least {$min}.";
        }
        return $this;
    }

    public function integer(string $field, mixed $value, int $min, int $max, ?string $message = null): self
    {
        if ($this->hasError($field)) {
            return $this;
        }
        if ($value === null || $value === '') {
            return $this;
        }
        if (!is_numeric($value) || (int) $value != $value) {
            $this->errors[$field] = $message ?? 'Please enter a whole number.';
            return $this;
        }
        $int = (int) $value;
        if ($int < $min || $int > $max) {
            $this->errors[$field] = $message ?? "Must be between {$min} and {$max}.";
        }
        return $this;
    }

    public function in(string $field, ?string $value, array $allowed, ?string $message = null): self
    {
        if ($this->hasError($field)) {
            return $this;
        }
        $value = (string) $value;
        if ($value !== '' && !in_array($value, $allowed, true)) {
            $this->errors[$field] = $message ?? 'Please select a valid option.';
        }
        return $this;
    }

    public function url(string $field, ?string $value, bool $required = false, ?string $message = null): self
    {
        $value = trim((string) $value);
        if ($value === '') {
            if ($required) {
                $this->errors[$field] = 'Please enter a valid URL.';
            }
            return $this;
        }
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            $this->errors[$field] = $message ?? 'Please enter a valid URL.';
        }
        return $this;
    }

    public function slug(string $field, ?string $value, ?string $message = null): self
    {
        if ($this->hasError($field)) {
            return $this;
        }
        $value = trim((string) $value);
        if ($value === '') {
            return $this;
        }
        if (!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $value)) {
            $this->errors[$field] = $message ?? 'Use lowercase letters, numbers, and hyphens only.';
        }
        return $this;
    }

    public function phone(string $field, ?string $value, ?string $message = null): self
    {
        if ($this->hasError($field)) {
            return $this;
        }
        $value = trim((string) $value);
        if ($value === '') {
            return $this;
        }
        if (!preg_match('/^[\d\s().+\-]{7,24}$/', $value)) {
            $this->errors[$field] = $message ?? 'Please enter a valid phone number.';
        }
        return $this;
    }

    public function password(string $field, ?string $value, int $min = 8, ?string $message = null): self
    {
        if ($this->hasError($field)) {
            return $this;
        }
        $value = (string) $value;
        if ($value !== '' && strlen($value) < $min) {
            $this->errors[$field] = $message ?? "Password must be at least {$min} characters.";
        }
        return $this;
    }

    public function confirmed(string $field, string $value, string $confirmation, ?string $message = null): self
    {
        if ($this->hasError($field)) {
            return $this;
        }
        if ($value !== $confirmation) {
            $this->errors[$field] = $message ?? 'Values do not match.';
        }
        return $this;
    }

    public function custom(string $field, bool $fails, string $message): self
    {
        if ($fails && !$this->hasError($field)) {
            $this->errors[$field] = $message;
        }
        return $this;
    }

    public function hasError(string $field): bool
    {
        return isset($this->errors[$field]);
    }

    /** @return array<string, string> */
    public function errors(): array
    {
        return $this->errors;
    }

    public function firstError(): ?string
    {
        return $this->errors === [] ? null : reset($this->errors);
    }

    public function fails(): bool
    {
        return $this->errors !== [];
    }
}
