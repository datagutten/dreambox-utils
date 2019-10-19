<?php


use datagutten\dreambox\web\timer;
use PHPUnit\Framework\TestCase;

class timerTest extends TestCase
{
    function setUp(): void
    {
        $file = sprintf('%s/channels_127.0.0.1.json',  realpath(__DIR__.'/../src'));
        //$string = '"1:0:19:12D:9:46:FFFF016A:0:0:0:":"NRK Super\/NRK3","1:0:19:515:9:46:FFFF016A:0:0:0:":"NRK Super\/NRK3 Lydteks","1:0:1:5EC:1:46:FFFF014A:0:0:0:":"NRK Tegnspr\u00e5k"';
        copy(__DIR__.'/channels_127.0.0.1.json', $file);
    }

    public function test__construct()
    {
        $timer = new timer('127.0.0.1');
        $this->assertIsArray($timer->channels);
        $this->assertSame('NRK Super/NRK3', $timer->channels['1:0:19:12D:9:46:FFFF016A:0:0:0:']);
    }

    public function testAdd_timer()
    {
        $timer = new timer('127.0.0.1');
        $start = strtotime('16:00');
        $end = strtotime('17:00');
        $response = $timer->add_timer('NRK Super/NRK3', $start, $end, 'test');
        $this->assertEquals('Timer \'test\' added', $response);
    }

    function tearDown(): void
    {
        $file = sprintf('%s/channels_127.0.0.1.json',  realpath(__DIR__.'/../src'));
        unlink($file);
    }
}
