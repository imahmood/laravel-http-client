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
     * Add a file to the request data.
     *
     * @return $this
     */
    public function addFile(string $fieldName, mixed $contents): static;

    /**
     * Get an array of files attached to the request.
     */
    public function getFiles(): array;

    /**
     * Get the content type of the request (e.g., JSON, FORM, etc.).
     */
    public function getContentType(): ContentType;
}
