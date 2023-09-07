<?php

namespace DannyXCII\HttpComponentTests;

use DannyXCII\HttpComponent\Response;
use DannyXCII\HttpComponentTests\Traits\ProvidesHeaderDataTrait;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    use ProvidesHeaderDataTrait;

    /**
     * @return void
     */
    public function testGetProtocolVersion(): void
    {
        $this->assertEquals('1.1', (new Response())->getProtocolVersion());
    }

    /**
     * @return void
     */
    public function testWithProtocolVersion(): void
    {
        $response = new Response();
        $response = $response->withProtocolVersion('2.0');

        $this->assertEquals('2.0', $response->getProtocolVersion());
    }

    /**
     * @return void
     */
    public function testGetHeaders(): void
    {
        $this->assertEquals(['content-type' => ['application/json']], ($this->getSimpleResponse())->getHeaders());
    }

    /**
     * @param string $key
     *
     * @dataProvider headerKeyProvider
     * @return void
     */
    public function testHasHeader(string $key): void
    {
        $this->assertTrue(($this->getSimpleResponse())->hasHeader($key));
    }

    /**
     * @return void
     */
    public function testGetHeader(): void
    {
        $this->assertEquals(['application/json'], ($this->getSimpleResponse())->getHeader('CONTENT-TYPE'));
    }

    /**
     * @return void
     */
    public function testWithHeader(): void
    {
        $response = $this->getSimpleResponse();
        $response = $response->withHeader('content-type', 'text/html');

        $this->assertEquals(['text/html'], $response->getHeader('content-type'));
    }

    /**
     * @return void
     */
    public function testWithAddedHeader(): void
    {
        $response = $this->getSimpleResponse();
        $response = $response->withAddedHeader('content-type', 'text/html');

        $this->assertEquals(['application/json', 'text/html'], $response->getHeader('content-type'));
    }

    /**
     * @return void
     */
    public function testGetHeaderLine(): void
    {
        $response = $this->getSimpleResponse();
        $response = $response->withAddedHeader('content-type', 'text/html');

        $this->assertEquals('application/json, text/html', $response->getHeaderLine('content-type'));
    }

    /**
     * @return void
     */
    public function testWithoutHeader(): void
    {
        $response = $this->getSimpleResponse();
        $response = $response->withoutHeader('CONTENT-TYPE');

        $this->assertEquals([], $response->getHeaders());
    }

    /**
     * @return void
     */
    public function testWithStatus(): void
    {
        $response = $this->getSimpleResponse();
        $response = $response->withStatus(404);

        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * @return Response
     */
    private function getSimpleResponse(): Response
    {
        return new Response(200, 'OK', ['Content-Type' => 'application/json']);
    }
}