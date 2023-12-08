# Laravel HTTP Client Wrapper
This is a simple wrapper around Laravel's HTTP Client that simplifies making HTTP requests and handling responses.
It provides an easy-to-use interface for common HTTP operations.

## Installation
You can install this package via Composer:

```
composer require imahmood/laravel-http-client
```

## Usage

### Sending a GET Request
To send a GET request, use the following code:

``` php
$request = new Request(HttpMethod::GET, 'https://example.com/api/users');
$response = ClientFactory::create()->send($request);
```

### Sending a POST Request
To send a POST request, you can do it like this:

```php
$body = [
    'username' => '__USERNAME__',
    'password' => '__PASSWORD__',
];

$request = new Request(HttpMethod::POST, 'https://example.com/api/auth/login', $body);
$response = ClientFactory::create()->send($request);
```

### Uploading Files
To upload files, attach them to the request using the `addFile` method:

```php
$request = new Request(HttpMethod::POST, 'https://example.com/api/users/1/avatar', $body);
$request->addFile('avatar', '/home/user/avatar.png');

$response = ClientFactory::create()->send($request);
```

### Sending a Request with a Bearer Token
If you need to send a request with a Bearer Token for authentication, you can do so like this:

```php
$accessToken = '__TOKEN__';

$request = new Request(HttpMethod::GET, 'https://example.com/api/users', $body);
$response = ClientFactory::createWithBearerToken($accessToken)->send($request);
```

### Request duration
You can easily retrieve the duration of a http request:

```php
$response = ClientFactory::create()->send($request);

echo $response->duration();
```

### Handling Unsuccessful Responses
You can easily throw an exception if the response is unsuccessful:

```php
$response = ClientFactory::create()->send($request);

try {
    $response->validate();
} catch (\Imahmood\HttpClient\Exceptions\ServerException $e) {
    // Handle the exception here
} catch (\Imahmood\HttpClient\Exceptions\ClientException $e) {
    // Handle the exception here
} catch (\Illuminate\Validation\ValidationException $e) {
    // Handle the exception here
}
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
