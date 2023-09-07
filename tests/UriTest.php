<?php

namespace DannyXCII\HttpComponentTests;

use DannyXCII\HttpComponent\Uri;
use PHPUnit\Framework\TestCase;

class UriTest extends TestCase
{
    /**
     * @return void
     */
    public function testGetScheme(): void
    {
        $this->assertEquals(
            'https',
            (new Uri('https', 'test.com', '/', ''))->getScheme()
        );
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
}