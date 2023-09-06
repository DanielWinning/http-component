<?php

namespace DannyXCII\HttpComponent\Web;

class WebServerURIResult
{
    private string $scheme;
    private string $host;
    private ?string $port;
    private string $path;
    private string $query;

    /**
     * @param string $scheme
     * @param string $host
     * @param string $path
     * @param string $query
     * @param ?string $port
     */
    public function __construct(string $scheme, string $host, string $path, string $query, ?string $port = null)
    {
        $this->scheme = $scheme;
        $this->host = $host;
        $this->port = $port;
        $this->path = strtok($path, '?');
        $this->query = $query;
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
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return string|null
     */
    public function getPort(): ?string
    {
        return $this->port;
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
}
