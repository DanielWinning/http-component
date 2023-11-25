<?php

namespace Luma\HttpComponent;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

class HttpClient implements ClientInterface
{
    /**
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $curl = curl_init();

        $this->setCurlOptions($curl, $request);

        $responseText = $this->getRawResponse($curl);

        $response = (new Response())->withStatus(curl_getinfo($curl, CURLINFO_HTTP_CODE));
        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $response = $this->parseHeaders($response, $responseText, $headerSize);

        return $response->withBody(StreamBuilder::build(substr($responseText, $headerSize)));
    }

    /**
     * @param string $endpoint
     * @param array $headers
     * @param string $body
     *
     * @return ResponseInterface
     */
    public function get(string $endpoint, array $headers = [], string $body = ''): ResponseInterface
    {
        return $this->buildAndSend('GET', $endpoint, $headers, $body);
    }

    /**
     * @param string $endpoint
     * @param array $headers
     * @param string $body
     *
     * @return ResponseInterface
     */
    public function post(string $endpoint, array $headers = [], string $body = ''): ResponseInterface
    {
        return $this->buildAndSend('POST', $endpoint, $headers, $body);
    }

    /**
     * @param string $endpoint
     * @param array $headers
     * @param string $body
     *
     * @return ResponseInterface
     */
    public function put(string $endpoint, array $headers = [], string $body = ''): ResponseInterface
    {
        return $this->buildAndSend('PUT', $endpoint, $headers, $body);
    }

    /**
     * @param string $endpoint
     * @param array $headers
     * @param string $body
     *
     * @return ResponseInterface
     */
    public function patch(string $endpoint, array $headers = [], string $body = ''): ResponseInterface
    {
        return $this->buildAndSend('PATCH', $endpoint, $headers, $body);
    }

    /**
     * @param string $endpoint
     * @param array $headers
     * @param string $body
     *
     * @return ResponseInterface
     */
    public function delete(string $endpoint, array $headers = [], string $body = ''): ResponseInterface
    {
        return $this->buildAndSend('DELETE', $endpoint, $headers, $body);
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array $headers
     * @param string $body
     *
     * @return ResponseInterface
     */
    private function buildAndSend(string $method, string $endpoint, array $headers, string $body): ResponseInterface
    {
        $request = new Request($method, $this->buildUriFromEndpoint($endpoint), $headers, StreamBuilder::build($body));

        return $this->sendRequest($request);
    }

    /**
     * @param string $endpoint
     *
     * @return UriInterface
     */
    private function buildUriFromEndpoint(string $endpoint): UriInterface
    {
        if (!parse_url($endpoint, PHP_URL_SCHEME)) {
            $endpoint = 'https://' . $endpoint;
        }

        $parts = parse_url($endpoint);

        return new Uri(
            $parts['scheme'],
            $parts['host'] ?? '',
            $parts['path'] ?? '/',
            $parts['query'] ?? '',
            $parts['port'] ?? ''
        );
    }

    /**
     * @param \CurlHandle $curl
     * @param RequestInterface $request
     *
     * @return void
     */
    private function setCurlOptions(\CurlHandle &$curl, RequestInterface $request): void
    {
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $request->getMethod());
        curl_setopt($curl, CURLOPT_URL, $request->getUri()->__toString());
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $request->getFlatHeaders());
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request->getBody()->getContents());
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
    }

    /**
     * @param \CurlHandle $curl
     *
     * @return bool|string
     *
     * @throws \RuntimeException
     */
    private function getRawResponse(\CurlHandle &$curl): bool|string
    {
        $responseText = curl_exec($curl);

        if (curl_errno($curl)) {
            throw new \RuntimeException(sprintf('cURL error: %s', curl_error($curl)));
        }

        curl_close($curl);

        return $responseText;
    }

    /**
     * @param ResponseInterface $response
     * @param string $responseText
     * @param int $headerSize
     *
     * @return ResponseInterface
     */
    private function parseHeaders(ResponseInterface &$response, string $responseText, int $headerSize): ResponseInterface
    {
        $responseHeaders = substr($responseText, 0, $headerSize);
        $headerLines = explode("\r\n", trim($responseHeaders));

        foreach ($headerLines as $headerLine) {
            $splitLine = explode(':', $headerLine, 2);
            [$key, $value] = count($splitLine) === 2 ? $splitLine : [$splitLine[0], null];

            $key = trim($key);
            $value = $value ? trim($value) : null;

            if ($key && $value) {
                if (array_key_exists($key, $response->getHeaders()) && in_array($value, $response->getHeader($key))) {
                    continue;
                }

                $response = $response->withAddedHeader($key, $value);
            }
        }

        return $response;
    }
}