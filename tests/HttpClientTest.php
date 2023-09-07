<?php

namespace DannyXCII\HttpComponentTests;

use DannyXCII\HttpComponent\HttpClient;
use DannyXCII\HttpComponent\Request;
use DannyXCII\HttpComponent\Uri;
use DannyXCII\HttpComponentTests\Traits\ProvidesHeaderDataTrait;
use PHPUnit\Framework\TestCase;

class HttpClientTest extends TestCase
{
    use ProvidesHeaderDataTrait;

    private static mixed $serverProcess;

    /**
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::$serverProcess = proc_open('php -S localhost:8000', [], $pipes);
    }

    /**
     * @return void
     */
    public static function tearDownAfterClass(): void
    {
        proc_terminate(self::$serverProcess);
        proc_close(self::$serverProcess);
    }

    /**
     * @return void
     */
    public function testNotFound(): void
    {
        $uri = new Uri('http', 'localhost', '/', '', 8000);
        $request = new Request('GET', $uri, []);

        $response = (new HttpClient())->sendRequest($request);

        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * @param string $method
     *
     * @dataProvider methodProvider
     *
     * @return void
     */
    public function testItSendsRequest(string $method): void
    {
        $uri = new Uri('http', 'localhost', '/tests/api.php', '', 8000);
        $request = new Request($method, $uri, []);

        $response = (new HttpClient())->sendRequest($request);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function testGet(): void
    {
        $this->assertEquals(200, (new HttpClient())->get(...$this->getDummyRequestData())->getStatusCode());
    }

    /**
     * @return void
     */
    public function testPost(): void
    {
        $this->assertEquals(200, (new HttpClient())->post(...$this->getDummyRequestData())->getStatusCode());
    }

    /**
     * @return void
     */
    public function testPut(): void
    {
        $this->assertEquals(200, (new HttpClient())->put(...$this->getDummyRequestData())->getStatusCode());
    }

    /**
     * @return void
     */
    public function testPatch(): void
    {
        $this->assertEquals(200, (new HttpClient())->patch(...$this->getDummyRequestData())->getStatusCode());
    }

    /**
     * @return void
     */
    public function testDelete(): void
    {
        $this->assertEquals(200, (new HttpClient())->delete(...$this->getDummyRequestData())->getStatusCode());
    }

    private function getDummyRequestData(): array
    {
        return [
            'http://localhost:8000/tests/api.php',
            [
                'Content-Type' => 'application/json',
            ],
            ''
        ];
    }
}