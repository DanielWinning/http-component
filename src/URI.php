<?php

namespace DannyXCII\Http;

use Psr\Http\Message\UriInterface;

class URI implements UriInterface
{
    private string $scheme;
    private string $host;
    private string $fragment;
    private string $port;
    private string $path;
    private string $query;

    public function __construct()
    {
        $this->host = '';
        $this->scheme = '';
    }

    private function parseUri(string $uri)
    {
        $parts = parse_url($uri);

        var_dump($parts);
        die();
    }

    /**
     * @return string
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    public function getAuthority(): string
    {
        // TODO: Implement getAuthority() method.
    }

    public function getUserInfo(): string
    {
        // TODO: Implement getUserInfo() method.
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): ?int
    {
        // TODO: Implement getPort() method.
    }

    public function getPath(): string
    {
        // TODO: Implement getPath() method.
    }

    public function getQuery(): string
    {
        // TODO: Implement getQuery() method.
    }

    public function getFragment(): string
    {
        // TODO: Implement getFragment() method.
    }

    public function withScheme(string $scheme): UriInterface
    {
        // TODO: Implement withScheme() method.
    }

    public function withUserInfo(string $user, ?string $password = null): UriInterface
    {
        // TODO: Implement withUserInfo() method.
    }

    public function withHost(string $host): UriInterface
    {
        // TODO: Implement withHost() method.
    }

    public function withPort(?int $port): UriInterface
    {
        // TODO: Implement withPort() method.
    }

    public function withPath(string $path): UriInterface
    {
        // TODO: Implement withPath() method.
    }

    public function withQuery(string $query): UriInterface
    {
        // TODO: Implement withQuery() method.
    }

    public function withFragment(string $fragment): UriInterface
    {
        // TODO: Implement withFragment() method.
    }

    public function __toString(): string
    {
        // TODO: Implement __toString() method.
    }
}