<?php
declare(strict_types=1);

namespace Imahmood\HttpClient;

use Illuminate\Http\Client\PendingRequest as LaravelPendingRequest;
use Illuminate\Http\Client\Response as LaravelHttpResponse;
use Illuminate\Support\Facades\Http as LaravelHttp;
use Imahmood\HttpClient\Enums\ContentType;

class Client implements ClientInterface
{
    /**
     * Default timeout.
     */
    protected int $timeout = 30;

    /**
     * @param  array<string, mixed>  $options
     * @param  array<string, string>  $headers
     */
    public function __construct(
        protected readonly array $options = [],
        protected readonly array $headers = [],
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function send(RequestInterface $request): ResponseInterface
    {
        $client = $this->httpClient();

        match ($request->getContentType()) {
            ContentType::JSON => $client->asJson()->acceptJson(),
            ContentType::FORM => $client->asForm(),
            ContentType::MULTIPART => $client->attach($request->getFiles()),
        };

        $startTime = microtime(true);

        $response = $client->{$request->getMethod()->value}($request->getUrl(), $request->getBody());

        $duration = microtime(true) - $startTime;

        return $this->convertResponse($request, $response, $duration);
    }

    /**
     * Convert a Laravel HTTP response to a custom ResponseInterface.
     */
    protected function convertResponse(
        RequestInterface $request,
        LaravelHttpResponse $response,
        float $duration,
    ): ResponseInterface {
        return new Response(
            $request->getUrl(),
            $response->body(),
            $response->headers(),
            $response->cookies(),
            $response->status(),
            $duration,
        );
    }

    /**
     * Create an HTTP client instance.
     */
    protected function httpClient(): LaravelPendingRequest
    {
        return LaravelHttp::connectTimeout($this->timeout)
            ->withOptions($this->options)
            ->withHeaders($this->headers);
    }
}
