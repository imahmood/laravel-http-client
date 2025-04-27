<?php
declare(strict_types=1);

namespace Imahmood\HttpClient;

use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Imahmood\HttpClient\Exceptions\ClientException;
use Imahmood\HttpClient\Exceptions\ServerException;

class Response implements ResponseInterface
{
    private array $decodedBody;

    public function __construct(
        private readonly string $url,
        private readonly string $body,
        private readonly array $headers,
        private readonly CookieJar $cookies,
        private readonly int $statusCode,
        private readonly float $duration,
    ) {}

    /**
     * {@inheritDoc}
     */
    public function body(): string
    {
        return $this->body;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        if (! isset($this->decodedBody)) {
            $this->decodedBody = (array) json_decode($this->body, true, 512, \JSON_BIGINT_AS_STRING | \JSON_THROW_ON_ERROR);
        }

        return $this->decodedBody;
    }

    /**
     * {@inheritDoc}
     */
    public function headers(): array
    {
        return $this->headers;
    }

    /**
     * {@inheritDoc}
     */
    public function cookies(): CookieJar
    {
        return $this->cookies;
    }

    /**
     * {@inheritDoc}
     */
    public function isJson(): bool
    {
        return Str::isJson($this->body);
    }

    /**
     * {@inheritDoc}
     */
    public function statusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * {@inheritDoc}
     */
    public function isSuccessful(): bool
    {
        return $this->statusCode >= 200 && $this->statusCode < 300;
    }

    /**
     * {@inheritDoc}
     */
    public function isClientError(): bool
    {
        return $this->statusCode >= 400 && $this->statusCode < 500;
    }

    /**
     * {@inheritDoc}
     */
    public function isServerError(): bool
    {
        return $this->statusCode >= 500;
    }

    /**
     * {@inheritDoc}
     */
    public function validate(): static
    {
        if ($this->statusCode === 422 && $this->isJson()) {
            $errors = $this->toArray()['errors'] ?? null;
            if ($errors) {
                throw ValidationException::withMessages($errors);
            }
        }

        if ($this->isClientError()) {
            throw new ClientException(
                sprintf('[HttpClient][ClientError] Url: %s, Response: %s', $this->url, $this->body())
            );
        }

        if ($this->isServerError()) {
            throw new ServerException(
                sprintf('[HttpClient][ServerError] Url: %s, Response: %s', $this->url, $this->body())
            );
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function duration(): float
    {
        return $this->duration;
    }
}
