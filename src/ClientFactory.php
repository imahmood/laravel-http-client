<?php

declare(strict_types=1);

namespace Imahmood\HttpClient;

class ClientFactory
{
    /**
     * Create a new instance with default settings.
     */
    public static function create(): ClientInterface
    {
        return new Client();
    }

    /**
     * Create a new instance with a Bearer Token in the headers.
     */
    public static function createWithBearerToken(string $accessToken): ClientInterface
    {
        $options = [
            'headers' => ['Authorization' => 'Bearer '.$accessToken],
        ];

        return new Client($options);
    }
}
