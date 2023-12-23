# Luma | HTTP Component

<div>
<!-- Version Badge -->
<img src="https://img.shields.io/badge/Version-2.0.5-blue" alt="Version 2.0.5">
<!-- PHP Coverage Badge -->
<img src="https://img.shields.io/badge/PHP Coverage-80.46%25-yellow" alt="PHP Coverage 80.46%">
<!-- License Badge -->
<img src="https://img.shields.io/badge/License-GPL--3.0--or--later-34ad9b" alt="License GPL--3.0--or--later">
</div>

The HTTP Component is a PHP library designed to simplify the process of making HTTP requests and handling HTTP responses 
in your applications. It follows the [PSR-7 HTTP Message Interface](https://www.php-fig.org/psr/psr-7/) standards for 
HTTP messages, making it compatible with other libraries and frameworks that also adhere to these standards.

## Installation

You can install this library using Composer:

```bash
composer require lumax/http-component
```

## Features

This HTTP Component package provides the following key features:

### Request and Response Handling

- `Request` and `Response` classes that implement the `Psr\Http\Message\RequestInterface` and `Psr\Http\Message\ResponseInterface`, respectively.
- Easily create and manipulate HTTP requests and responses.
- Handle headers, request methods, status codes, and more.

### Stream Handling

- A `Stream` class that implements the `Psr\Http\Message\StreamInterface` for working with stream data.
- Read and write data to streams, check for stream availability, and more.

### HTTP Client

- A `HttpClient` class that implements the `Psr\Http\Client\ClientInterface`.
- Simplifies sending HTTP requests using cURL and processing HTTP responses.
- Supports common HTTP methods like GET, POST, PUT, PATCH and DELETE.
- Automatically parses response headers and handles redirects.

### URI Handling

- A `Uri` class that implements the `Psr\Http\Message\UriInterface` for working with URIs.
- Easily construct and manipulate URIs, including handling scheme, host, port, path, query, and fragment.

## Usage

### Creating an HTTP Request

```php
use Luma\HttpComponent\HttpClient;
use Luma\HttpComponent\Request;
use Luma\HttpComponent\StreamBuilder;
use Luma\HttpComponent\Uri;

// Create a URI
$uri = new Uri('https', 'example.com', '/');

// Create an HTTP GET request
$body = 'Some text!';
$request = new Request(
    'GET', 
    $uri, 
    ['Content-Type' => 'application/json'], 
    StreamBuilder::build($body)
);

// Customise the request headers
$request = $request->withHeader('Authorization', 'Bearer AccessToken');

// Send the request using the built-in HTTP Client
$response = (new HttpClient())->sendRequest($request);

// Get the response status code
$status = $response->getStatusCode();

// Get the response body
$body = $response->getBody()->getContents();
```

### Creating an HTTP Client

```php
use Luma\HttpComponent\HttpClient;

// Create an HTTP client
$client = new HttpClient();

// Send GET request
$response = $client->get('https://example.com/api/resource');

// Send POST request to endpoint with headers and body
$response = $client->post(
    'https://example.com/api/resource', 
    ['Content-Type' => 'application/json', 'Authorization' => 'Bearer AccessToken'], 
    json_encode(['data' => 'value'])
);
```

### Working with Streams

```php
use Luma\HttpComponent\StreamBuilder;

// Create a stream from a string
$stream = StreamBuilder::build('Hello, World!');

// Read from the stream
$data = $stream->read(1024);

// Write to the stream
$stream->write('New data to append');

// Rewind the streams internal pointer
$stream->rewind();

// Get the stream contents
$contents = $stream->getContents();
```

### URI Handling

```php
use Luma\HttpComponent\Uri;
use Luma\HttpComponent\Web\WebServerUri;

// Create a URI
$uri = new Uri('https', 'example.com', '/api/resource');

// Modify the URI
$uri = $uri->withScheme('http');
$uri = $uri->withPort(8888);
$uri = $uri->withQuery('new_param=new_value');

// Get the URI as a string
$uriString = $uri->__toString();

// Build a URI based on the current request to your web server
$uri = WebServerUri::generate();
```

### License
This package is open-source software licensed under the 
[GNU General Public License, version 3.0 (GPL-3.0)](https://opensource.org/licenses/GPL-3.0).