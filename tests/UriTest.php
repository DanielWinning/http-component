<?php

namespace DannyXCII\HttpComponentTests;

use DannyXCII\HttpComponent\Uri;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriInterface;

class UriTest extends TestCase
{
    /**
     * @return void
     */
    public function testGetScheme(): void
    {
        $this->assertEquals('https', ($this->getDefaultUri())->getScheme());
    }

    /**
     * @return void
     */
    public function testGetAuthority(): void
    {
        $uri = new Uri('https', 'test.com', '/path', 'var=1', 443);
        $this->assertEquals('test.com:443', $uri->getAuthority());

        $uri = new Uri('ftp', 'user:password@test.com', '/path', 'var=1');
        $this->assertEquals('user:password@test.com', $uri->getAuthority());
    }

    /**
     * @return void
     */
    public function testWithPath(): void
    {
        $this->assertEquals('/', ($this->getDefaultUri())->getPath());
        $this->assertEquals('/new', ($this->getDefaultUri())->withPath('/new')->getPath());
    }

    /**
     * @return void
     */
    public function testToString(): void
    {
        $this->assertEquals('https://test.com/', ($this->getDefaultUri())->__toString());
    }

    /**
     * @return void
     */
    public function testWithUserInfo(): void
    {
        $this->assertEquals(
            'username:password',
            ($this->getDefaultUri())->withUserInfo('username', 'password')->getUserInfo()
        );
    }

    /**
     * @return void
     */
    public function testWithScheme(): void
    {
        $this->assertEquals('ftp', ($this->getDefaultUri())->withScheme('ftp')->getScheme());
    }

    /**
     * @return void
     */
    public function testWithHost(): void
    {
        $this->assertEquals('localhost', ($this->getDefaultUri())->withHost('localhost')->getHost());
    }

    /**
     * @return void
     */
    public function testWithPort(): void
    {
        $this->assertEquals(8080, ($this->getDefaultUri())->withPort(8080)->getPort());
    }

    /**
     * @return void
     */
    public function testWithQuery(): void
    {
        $this->assertEquals(
            'name=test&hello=world',
            ($this->getDefaultUri())->withQuery('name=test&hello=world')->getQuery()
        );
    }

    /**
     * @return void
     */
    public function testWithFragment(): void
    {
        $this->assertEquals('news', ($this->getDefaultUri())->withFragment('news')->getFragment());
    }

    /**
     * @return UriInterface
     */
    private function getDefaultUri(): UriInterface
    {
        return new Uri('https', 'test.com', '/', '');
    }
}