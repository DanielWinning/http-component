<?php

namespace DannyXCII\HttpComponent;

use Psr\Http\Message\UriInterface;

class Uri implements UriInterface
{
    private string $scheme;
    private string $host;
    private ?string $port;
    private string $path;
    private string $query;
    private string $fragment;
    private string $userInfo;

    public function __construct(string $scheme, string $host, string $path, string $query, string|int $port = null)
    {
        $this->scheme = $scheme;
        $this->host = $host;
        $this->port = (string) $port;
        $this->path = $path;
        $this->query = $query;
        $this->fragment = '';
        $this->userInfo = '';
    }

    /**
     * @return string
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * @return string
     */
    public function getAuthority(): string
    {
        $authority = $this->host;

        if (!empty($this->userInfo)) {
            $authority = sprintf('%s@%s', $this->userInfo, $authority);
        }

        if (!empty($this->port)) {
            $authority = sprintf('%s:%s', $authority, $this->port);
        }

        return $authority;
    }

    /**
     * @return string
     */
    public function getUserInfo(): string
    {
        return $this->userInfo;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return int|null
     */
    public function getPort(): ?int
    {
        return (int) $this->port;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * @return string
     */
    public function getFragment(): string
    {
        return $this->fragment;
    }

    /**
     * @param string $scheme
     *
     * @return UriInterface
     */
    public function withScheme(string $scheme): UriInterface
    {
        $uri = clone $this;
        $uri->scheme = $scheme;

        return $uri;
    }

    /**
     * @param string $user
     * @param string|null $password
     *
     * @return UriInterface
     */
    public function withUserInfo(string $user, ?string $password = null): UriInterface
    {
        $uri = clone $this;
        $uri->userInfo = $password ? sprintf('%s:%s', $user, $password) : $user;

        return $uri;
    }

    /**
     * @param string $host
     *
     * @return UriInterface
     */
    public function withHost(string $host): UriInterface
    {
        $uri = clone $this;
        $uri->host = $host;

        return $uri;
    }

    /**
     * @param int|null $port
     *
     * @return UriInterface
     */
    public function withPort(?int $port): UriInterface
    {
        $uri = clone $this;
        $uri->port = $port;

        return $uri;
    }

    /**
     * @param string $path
     *
     * @return UriInterface
     */
    public function withPath(string $path): UriInterface
    {
        $uri = clone $this;
        $uri->path = $path;

        return $uri;
    }

    /**
     * @param string $query
     *
     * @return UriInterface
     */
    public function withQuery(string $query): UriInterface
    {
        $uri = clone $this;
        $uri->query = $query;

        return $uri;
    }

    /**
     * @param string $fragment
     *
     * @return UriInterface
     */
    public function withFragment(string $fragment): UriInterface
    {
        $uri = clone $this;
        $uri->fragment = $fragment;

        return $uri;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $uri = sprintf('%s://%s', $this->scheme, $this->host);

        if (!empty($this->port)) {
            $uri .= sprintf(':%d', $this->port);
        }

        $uri .= $this->path;

        if (!empty($this->query)) {
            $uri .= sprintf('?%s', $this->query);
        }

        if (!empty($this->fragment)) {
            $uri .= sprintf('#%s', $this->fragment);
        }

        return $uri;
    }
}