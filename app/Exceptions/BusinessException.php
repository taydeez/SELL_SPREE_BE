<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class BusinessException extends Exception
{
    public function __construct(
        string $message,
        private readonly int $statusCode = 400,
        private readonly mixed $errors = null,
    ) {
        parent::__construct($message);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getErrors(): mixed
    {
        return $this->errors;
    }

    // ─── Factory Methods ─────────────────────────────────────────────────────

    public static function notFound(string $resource, mixed $id = null): self
    {
        $message = $id
            ? "{$resource} with id '{$id}' not found."
            : "{$resource} not found.";

        return new self($message, JsonResponse::HTTP_NOT_FOUND);
    }

    public static function unauthorized(string $message = 'Unauthorized.'): self
    {
        return new self($message, JsonResponse::HTTP_UNAUTHORIZED);
    }

    public static function forbidden(string $message = 'Forbidden.'): self
    {
        return new self($message, JsonResponse::HTTP_FORBIDDEN);
    }

    public static function alreadyExists(string $resource): self
    {
        return new self("{$resource} already exists.", JsonResponse::HTTP_CONFLICT);
    }

    public static function invalidCredentials(): self
    {
        return new self('Invalid credentials.', JsonResponse::HTTP_UNAUTHORIZED);
    }

    public static function suspended(): self
    {
        return new self('Account has been suspended.', JsonResponse::HTTP_FORBIDDEN);
    }

    public static function cannotUpdate(string $resource, mixed $id = null): self
    {
        $message = $id
            ? "Cannot update {$resource} '{$id}'."
            : "Cannot update {$resource}.";

        return new self($message, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    public static function invalidOperation(string $message): self
    {
        return new self($message, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }
}
