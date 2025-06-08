<?php
declare(strict_types=1);

namespace Imahmood\HttpClient;

use Imahmood\HttpClient\Enums\ContentType;
use Imahmood\HttpClient\Enums\HttpMethod;

interface RequestInterface
{
    /**
     * Get the HTTP method of the request (e.g., GET, POST, etc.).
     */
    public function getMethod(): HttpMethod;

    /**
     * Get the URL to which the request is sent.
     */
    public function getUrl(): string;

    /**
     * Get the body content of the HTTP request.
     */
    public function getBody(): array;

    /**
     * Add a file from a given path to the request payload.
     *
     * @return $this
     */
    public function addFile(string $fieldName, string $path): static;

    /**
     * Add a file using its raw contents to the request payload.
     *
     * @return $this
     */
    public function addRawFile(string $fieldName, string $contents): static;

    /**
     * Get an array of files attached to the request.
     */
    public function getFiles(): array;

    /**
     * Get the content type of the request (e.g., JSON, FORM, etc.).
     */
    public function getContentType(): ContentType;

    /**
     * Add a cookie to the request.
     */
    public function addCookie(string $name, string $value): static;

    /**
     * Get all cookies for the request.
     */
    public function getCookies(): array;
}
