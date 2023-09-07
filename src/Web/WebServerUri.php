<?php

namespace DannyXCII\HttpComponent\Web;

class WebServerUri
{
    public static function generate(): WebServerUriResult
    {
        $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $path = $_SERVER['REQUEST_URI'];
        $query = $_SERVER['QUERY_STRING'] ?? '';
        $port = $_SERVER['SERVER_PORT'] ?? null;

        return new WebServerUriResult($scheme, $host, $path, $query, $port);
    }
}