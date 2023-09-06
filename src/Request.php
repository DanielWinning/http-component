<?php

namespace DannyXCII\HttpComponent;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class Request implements RequestInterface
{
    private UriInterface $uri;
    private StreamInterface $body;
    private string $method;
    private array $headers;
    private string $protocolVersion;

    public function __construct(
        string $method,
        UriInterface $uri,
        array $headers = [],
        StreamInterface $body = null,
        string $protocolVersion = '1.1'
    ) {
        $this->method = $method;
        $this->uri = $uri;
        $this->headers = $headers;
        $this->body = $body ?? new Stream(fopen('php://temp', 'r+'));
        $this->protocolVersion = $protocolVersion;
    }

    /**
     * @return string
     */
    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    /**
     * @param string $version
     *
     * @return MessageInterface
     */
    public function withProtocolVersion(string $version): MessageInterface
    {
        $request = clone $this;
        $request->protocolVersion = $version;

        return $request;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasHeader(string $name): bool
    {
        return isset($this->headers[strtolower($name)]);
    }

    /**
     * @param string $name
     *
     * @return array|string[]
     */
    public function getHeader(string $name): array
    {
        return $this->headers[strtolower($name)] ?? [];
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function getHeaderLine(string $name): string
    {
        $header = $this->getHeader($name);

        return implode(', ', $header);
    }

    /**
     * @param string $name
     * @param $value
     *
     * @return MessageInterface
     */
    public function withHeader(string $name, $value): MessageInterface
    {
        $request = clone $this;
        $request->headers[strtolower($name)] = is_array($value) ? $value : [$value];

        return $request;
    }

    /**
     * @param string $name
     * @param $value
     *
     * @return MessageInterface
     */
    public function withAddedHeader(string $name, $value): MessageInterface
    {
        $request = clone $this;
        $request->headers[$name] = array_merge($this->headers[$name] ?? [], is_array($value) ? $value : []);

        return $request;
    }

    /**
     * @param string $name
     *
     * @return MessageInterface
     */
    public function withoutHeader(string $name): MessageInterface
    {
        $request = clone $this;
        unset($request->headers[strtolower($name)]);

        return $request;
    }

    /**
     * @return StreamInterface
     */
    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    /**
     * @param StreamInterface $body
     *
     * @return MessageInterface
     */
    public function withBody(StreamInterface $body): MessageInterface
    {
        $request = clone $this;
        $request->body = $body;

        return $request;
    }

    /**
     * @return string
     */
    public function getRequestTarget(): string
    {
        return $this->uri->getPath() ?: '/';
    }

    /**
     * @param string $requestTarget
     *
     * @return RequestInterface
     */
    public function withRequestTarget(string $requestTarget): RequestInterface
    {
        $request = clone $this;
        $request->uri = $request->uri->withPath($requestTarget);

        return $request;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     *
     * @return RequestInterface
     */
    public function withMethod(string $method): RequestInterface
    {
        $request = clone $this;
        $request->method = $method;

        return $request;
    }

    /**
     * @return UriInterface
     */
    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    /**
     * @param UriInterface $uri
     * @param bool $preserveHost
     *
     * @return RequestInterface
     */
    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
    {
        $request = clone $this;
        $request->uri = $uri;

        if (!$preserveHost) {
            $hostHeader = $request->getHeaderLine('host');
            $request->uri = $request->uri->withHost($hostHeader);
        }

        return $request;
    }
}