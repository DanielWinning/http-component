<?php

namespace DannyXCII\HttpComponent\Traits;

trait ResolveHeadersTrait
{
    /**
     * @param array $headers
     *
     * @return array
     */
    protected function setHeaders(array $headers): array
    {
        $sortedHeaders = [];

        foreach ($headers as $key => $header) {
            $sortedHeaders[strtolower($key)] = is_array($header) ? $header : [$header];
        }

        return $sortedHeaders;
    }
}