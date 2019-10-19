<?php


use datagutten\dreambox\web\common;
use PHPUnit\Framework\TestCase;

class commonTest extends TestCase
{

    public function test__construct()
    {
        $dreambox = new common('127.0.0.1');
        $this->assertSame('127.0.0.1', $dreambox->dreambox_ip);
    }

    public function testChannel_file()
    {
        $dreambox = new common('127.0.0.1');
        $file = $dreambox->channel_file();
        $this->assertSame(sprintf('%s/channels_127.0.0.1.json',  realpath(__DIR__.'/../src')), $file);
    }
}
