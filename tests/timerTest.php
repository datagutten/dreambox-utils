<?php


use datagutten\dreambox\web\objects;
use datagutten\dreambox\web\timer;
use datagutten\tools\files\files;
use PHPUnit\Framework\TestCase;

class timerTest extends TestCase
{
    /**
     * @var string
     */
    private $channel_file;
    /**
     * @var array|false|string
     */
    private $dreambox_ip;

    function setUp(): void
    {
        //$string = '"1:0:19:12D:9:46:FFFF016A:0:0:0:":"NRK Super\/NRK3","1:0:19:515:9:46:FFFF016A:0:0:0:":"NRK Super\/NRK3 Lydteks","1:0:1:5EC:1:46:FFFF014A:0:0:0:":"NRK Tegnspr\u00e5k"';
        $ip_env = getenv('DREAMBOX_IP');
        if(!empty($ip_env))
            $this->dreambox_ip = $ip_env;
        else
            $this->dreambox_ip = '127.0.0.1';
        $this->channel_file = files::path_join(__DIR__, 'test_channels_127.0.0.1.json');
    }

    public function test__construct()
    {
        $timer = new timer($this->dreambox_ip, $this->channel_file);
        $this->assertIsArray($timer->channels);
        $this->assertSame('NRK Super/NRK3', $timer->channels['1:0:19:12D:9:46:FFFF016A:0:0:0:']);
    }
    public function testGetChannelId()
    {
        $timer = new timer($this->dreambox_ip, $this->channel_file);
        $channel_id = $timer->channel_id('NRK Super/NRK3');
        $this->assertSame('1:0:19:12D:9:46:FFFF016A:0:0:0:', $channel_id);
    }

    public function testAdd_timer()
    {
        $timer = new timer($this->dreambox_ip, $this->channel_file);
        $start = strtotime('16:00');
        $end = strtotime('17:00');
        $channel_id = $timer->channel_id('NRK Super/NRK3');
        $response = $timer->add_timer($channel_id, $start, $end, 'test');
        $this->assertEquals('Timer \'test\' added', $response);
    }

    public function testGetTimers()
    {
        $timer = new timer($this->dreambox_ip, $this->channel_file);
        $timers = $timer->get_timers();
        $this->assertIsArray($timers);
        $this->assertInstanceOf(objects\timer::class, $timers[0]);
        $this->assertSame('Nat Geo HD (N)', $timers[1]->channel_name);
    }

    public function testHasTimer()
    {
        $timer = new timer($this->dreambox_ip, $this->channel_file);
        $status = $timer->has_timer('Nat Geo HD (N)', strtotime('2020-11-16 03:55'), strtotime('2020-11-16 05:05'));
        $this->assertInstanceOf(objects\timer::class, $status);
    }
}
