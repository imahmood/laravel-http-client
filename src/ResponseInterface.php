<?php
declare(strict_types=1);

namespace Imahmood\HttpClient;

use GuzzleHttp\Cookie\CookieJarInterface;

interface ResponseInterface
{
    /**
     * Get the response body as a string.
     */
    public function body(): string;

    /**
     * Get the response body as an array.
     *
     * @throws \JsonException
     */
    public function toArray(): array;

    /**
     * Get the response headers.
     */
    public function headers(): array;

    /**
     * Get the cookies from the response.
     */
    public function cookies(): CookieJarInterface;

    /**
     * Check if the response content is JSON.
     */
    public function isJson(): bool;

    /**
     * Get the HTTP status code of the response.
     */
    public function statusCode(): int;

    /**
     * Check if the response is successful (status code 2xx).
     */
    public function isSuccessful(): bool;

    /**
     * Check if the response indicates a client error (status code 4xx).
     */
    public function isClientError(): bool;

    /**
     * Check if the response indicates a server-side error (status code 5xx).
     */
    public function isServerError(): bool;

    /**
     * Validate the response and throw exceptions if necessary.
     *
     * @return $this
     *
     * @throws \Imahmood\HttpClient\Exceptions\ClientException
     * @throws \Imahmood\HttpClient\Exceptions\ServerException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validate(): static;

    /**
     * Get the duration of the request in seconds.
     */
    public function duration(): float;
}
