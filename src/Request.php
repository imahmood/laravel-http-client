<?php
declare(strict_types=1);

namespace Imahmood\HttpClient;

use Imahmood\HttpClient\Enums\ContentType;
use Imahmood\HttpClient\Enums\HttpMethod;

class Request implements RequestInterface
{
    protected array $files = [];

    protected ContentType $contentType = ContentType::JSON;

    public function __construct(
        protected HttpMethod $method,
        protected string $url,
        protected array $body = [],
    ) {
    }

    /**
     * @return $this
     */
    public function setMethod(HttpMethod $method): static
    {
        $this->method = $method;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getMethod(): HttpMethod
    {
        return $this->method;
    }

    /**
     * @return $this
     */
    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return $this
     */
    public function setBody(array $body): static
    {
        $this->body = $body;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getBody(): array
    {
        return $this->body;
    }

    /**
     * {@inheritDoc}
     */
    public function addFile(string $fieldName, string $path): static
    {
        $this->contentType = ContentType::MULTIPART;
        $this->files[] = [
            'fieldName' => $fieldName,
            'path' => $path,
        ];

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getFiles(): array
    {
        $data = [];

        foreach ($this->files as $file) {
            $data[] = [
                $file['fieldName'],
                file_get_contents($file['path']),
            ];
        }

        return $data;
    }

    /**
     * @return $this
     */
    public function setContentType(ContentType $contentType): self
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getContentType(): ContentType
    {
        return $this->contentType;
    }
}
