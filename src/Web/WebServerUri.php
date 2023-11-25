<?php

namespace Luma\HttpComponent\Web;

use Luma\HttpComponent\Uri;

class WebServerUri
{
    /**
     * @return Uri
     */
    public static function generate(): Uri
    {
        $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $urlParts = parse_url($scheme . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        $host = $urlParts['host'];

        return new Uri(
            $scheme,
            $host,
            $urlParts['path'],
            $urlParts['query'] ?? '',
            $urlParts['port'] ?? ($_SERVER['SERVER_PORT'] ?? null)
        );
    }
}