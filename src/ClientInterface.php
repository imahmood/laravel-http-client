<?php
declare(strict_types=1);

namespace Imahmood\HttpClient;

interface ClientInterface
{
    /**
     * Send an HTTP request.
     */
    public function send(RequestInterface $request): ResponseInterface;
}
