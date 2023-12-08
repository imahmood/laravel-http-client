<?php
declare(strict_types=1);

namespace Imahmood\HttpClient\Tests;

use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Imahmood\HttpClient\ClientFactory;
use Imahmood\HttpClient\Enums\ContentType;
use Imahmood\HttpClient\Enums\HttpMethod;
use Imahmood\HttpClient\Exceptions\ClientException;
use Imahmood\HttpClient\Exceptions\ServerException;
use Imahmood\HttpClient\Request;

class ClientTest extends TestCase
{
    public function testGetRequest(): void
    {
        Http::fake([
            'https://reqres.in/*' => Http::response('ok', 200),
        ]);

        $request = new Request(HttpMethod::GET, 'https://reqres.in/api/users?page=2');
        $response = ClientFactory::create()->send($request);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(200, $response->statusCode());
        $this->assertEquals('ok', $response->body());
    }

    public function testPostRequest(): void
    {
        $body = [
            'id' => 100,
            'name' => 'user name',
        ];

        Http::fake([
            'https://reqres.in/*' => Http::response($body, 201),
        ]);

        $request = new Request(HttpMethod::POST, 'https://reqres.in/api/users', $body);
        $response = ClientFactory::create()->send($request);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(201, $response->statusCode());
        $this->assertEquals($body, $response->toArray());
    }

    public function testPutRequest(): void
    {
        $body = [
            'name' => 'user name',
        ];

        Http::fake([
            'https://reqres.in/*' => Http::response($body, 200),
        ]);

        $request = new Request(HttpMethod::PUT, 'https://reqres.in/api/users/100', $body);
        $response = ClientFactory::create()->send($request);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(200, $response->statusCode());
        $this->assertEquals($body, $response->toArray());
    }

    public function testPatchRequest(): void
    {
        $body = [
            'name' => 'user name',
        ];

        Http::fake([
            'https://reqres.in/*' => Http::response($body, 200),
        ]);

        $request = new Request(HttpMethod::PATCH, 'https://reqres.in/api/users/100', $body);
        $response = ClientFactory::create()->send($request);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(200, $response->statusCode());
        $this->assertEquals($body, $response->toArray());
    }

    public function testDeleteRequest(): void
    {
        Http::fake([
            'https://reqres.in/*' => Http::response(null, 204),
        ]);

        $request = new Request(HttpMethod::DELETE, 'https://reqres.in/api/users/100');
        $response = ClientFactory::create()->send($request);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(204, $response->statusCode());
    }

    public function testClientErrorResponses(): void
    {
        Http::fake([
            'https://reqres.in/*' => Http::response([], 401),
        ]);

        $request = new Request(HttpMethod::PUT, 'https://reqres.in/api/users/100', []);
        $response = ClientFactory::create()->send($request);

        $this->assertEquals(401, $response->statusCode());

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isClientError());
        $this->assertFalse($response->isServerError());

        $this->expectException(ClientException::class);
        $response->validate();
    }

    public function testServerErrorResponses(): void
    {
        Http::fake([
            'https://reqres.in/*' => Http::response([], 500),
        ]);

        $request = new Request(HttpMethod::PUT, 'https://reqres.in/api/users/100', []);
        $response = ClientFactory::create()->send($request);

        $this->assertEquals(500, $response->statusCode());

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isClientError());
        $this->assertTrue($response->isServerError());

        $this->expectException(ServerException::class);
        $response->validate();
    }

    public function testValidationErrorResponses(): void
    {
        Http::fake([
            'https://reqres.in/*' => Http::response(['errors' => ['username' => []]], 422),
        ]);

        $request = new Request(HttpMethod::PUT, 'https://reqres.in/api/users/100', []);
        $response = ClientFactory::create()->send($request);

        $this->assertEquals(422, $response->statusCode());

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isClientError());
        $this->assertFalse($response->isServerError());

        $this->expectException(ValidationException::class);
        $response->validate();
    }

    public function testUploadFile(): void
    {
        Http::fake([
            'https://reqres.in/*' => Http::response([], 200),
        ]);

        $request = new Request(HttpMethod::POST, 'https://reqres.in/api/users/100/avatar');
        $request->addFile('avatar', __DIR__.'/TestSupport/assets/avatar.jpg');

        $this->assertEquals(ContentType::MULTIPART->value, $request->getContentType()->value);

        $response = ClientFactory::create()->send($request);

        $this->assertTrue($response->isSuccessful());
    }
}
