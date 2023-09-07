<?php

namespace DannyXCII\HttpComponent;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

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

        $responseText = curl_exec($curl);

        if (curl_errno($curl)) {
            throw new \RuntimeException(sprintf('cURL error: %s', curl_error($curl)));
        }

        curl_close($curl);

        $response = new Response();
        $response = $response->withStatus(curl_getinfo($curl, CURLINFO_HTTP_CODE));

        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);

        $response = $this->parseHeaders($response, $responseText, $headerSize);

        $responseBody = substr($responseText, $headerSize);

        return $response->withBody(StreamBuilder::build($responseBody));
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
        curl_setopt($curl, CURLOPT_HTTPHEADER, $request->getHeaders());
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