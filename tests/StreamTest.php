<?php

namespace Luma\HttpComponentTests;

use Luma\HttpComponent\Stream;
use PHPUnit\Framework\TestCase;

class StreamTest extends TestCase
{
    /**
     * @return void
     */
    public function testToString(): void
    {
        [$stream, $data] = $this->getWritableStreamWithData();

        $this->assertEquals($data, $stream->__toString());

        $stream->close();
    }

    /**
     * @return void
     */
    public function testGetSize(): void
    {
        [$stream, $data] = $this->getWritableStreamWithData();

        $this->assertEquals(strlen($data), $stream->getSize());

        $stream->close();
    }

    /**
     * @return void
     */
    public function testTell(): void
    {
        [$stream] = $this->getWritableStreamWithData();

        $this->assertEquals(0, $stream->tell());
        $stream->seek(5);
        $this->assertEquals(5, $stream->tell());

        $stream->close();
    }

    /**
     * @return void
     */
    public function testClose(): void
    {
        $resource = fopen('php://temp', 'r+');
        $stream = new Stream($resource);

        $this->assertTrue(is_resource($resource));

        $stream->close();

        $this->assertFalse(is_resource($resource));
    }

    /**
     * @return void
     */
    public function testDetach(): void
    {
        $resource = fopen('php://temp', 'r+');
        $stream = new Stream($resource);

        $detachedResource = $stream->detach();

        $this->assertSame($resource, $detachedResource);
        $this->assertNull($stream->detach());

        $stream->close();
    }

    /**
     * @return void
     */
    public function testEof(): void
    {
        [$stream, $data] = $this->getWritableStreamWithData();

        $this->assertFalse($stream->eof());

        $stream->read(strlen($data) + 1);

        $this->assertTrue($stream->eof());

        $stream->close();
    }

    /**
     * @return void
     */
    public function testIsSeekableWithSeekableResource(): void
    {
        [$stream] = $this->getWritableStreamWithData();

        $this->assertTrue($stream->isSeekable());

        $stream->close();
    }

    /**
     * @return void
     */
    public function testSeek(): void
    {
        [$stream] = $this->getWritableStreamWithData();

        $stream->seek(5);

        $this->assertEquals('string', $stream->getContents());

        $stream->close();
    }

    /**
     * @return void
     */
    public function testRead(): void
    {
        [$stream] = $this->getWritableStreamWithData();

        $read = [];

        $read[] = $stream->read(4);
        $read[] = $stream->read(7);
        $read[] = $stream->read(12);

        $this->assertSame('Test', $read[0]);
        $this->assertSame(' string', $read[1]);
        $this->assertSame('', $read[2]);
    }

    /**
     * @return void
     */
    public function testWrite(): void
    {
        $resource = fopen('php://temp', 'r+');
        $stream = new Stream($resource);

        $writeData = 'Some text';
        $stream->write($writeData);

        $stream->rewind();

        $this->assertSame(strlen($writeData), $stream->getSize());
        $this->assertSame($writeData, $stream->getContents());

        $stream->close();
    }

    /**
     * @return void
     */
    public function testGetMetadata(): void
    {
        [$stream] = $this->getWritableStreamWithData();

        $this->assertNotNull($stream->getMetadata('wrapper_type'));

        $stream->close();
    }

    /**
     * @return array
     */
    private function getWritableStreamWithData(): array
    {
        $data = 'Test string';
        $resource = fopen('php://temp', 'r+');
        fwrite($resource, 'Test string');
        $stream = new Stream($resource);

        return [$stream, $data];
    }
}