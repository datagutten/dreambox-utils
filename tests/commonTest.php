<?php /** @noinspection PhpUnhandledExceptionInspection */

namespace datagutten\dreambox\web_tests;

use datagutten\dreambox\web\common;
use datagutten\dreambox\web\exceptions\DreamboxException;
use datagutten\dreambox\web\exceptions\DreamboxHTTPException;
use FileNotFoundException;
use InvalidArgumentException;

class commonTest extends DreamboxTestCase
{
    public function testEmptyAddress()
    {
        $this->expectException(InvalidArgumentException::class);
        new common('');
    }

    public function testInvalidAddress()
    {
        $this->expectException(DreamboxHTTPException::class);
        new common('httpbin.org/status/404');
    }

    public function testInvalidResponse()
    {
        $this->expectException(DreamboxException::class);
        $this->expectExceptionMessage('Dreambox not found at');
        new common('httpbin.org/anything/not_dreambox');
    }

    public function testLoadChannelListInvalid()
    {
        $this->expectException(FileNotFoundException::class);
        $common = new common($this->dreambox_ip);
        $common->load_channel_list('bad');
    }

    public function testLoadChannelListEmpty()
    {
        $this->expectException(DreamboxException::class);
        $this->expectExceptionMessage('Channel list empty');
        $common = new common($this->dreambox_ip);
        $fp = tmpfile();
        fwrite($fp, json_encode([]));
        $path = stream_get_meta_data($fp)['uri'];
        $common->load_channel_list($path);
    }
}
