<?php /** @noinspection PhpUnhandledExceptionInspection */

namespace datagutten\dreambox\web_tests;

use datagutten\dreambox\web\exceptions\DreamboxException;
use datagutten\dreambox\web\exceptions\DreamboxHTTPException;
use datagutten\dreambox\web\objects;
use datagutten\dreambox\web\timer;
use datagutten\tools\files\files;
use InvalidArgumentException;

class timerTest extends DreamboxTestCase
{
    /**
     * @var string
     */
    private string $channel_file;

    function setUp(): void
    {
        parent::setUp();
        $this->channel_file = files::path_join(__DIR__, 'test_channels_127.0.0.1.json');
        date_default_timezone_set('Europe/Oslo');
    }

    public function test__construct()
    {
        $timer = new timer($this->dreambox_ip, $this->channel_file);
        $this->assertIsArray($timer->channels);
        $this->assertSame('NRK Super/NRK3', $timer->channels['1:0:19:12D:9:46:FFFF016A:0:0:0:']);
    }

    public function test__constructTimer()
    {
        $timer = new objects\timer('1:0:19:12D:9:46:FFFF016A:0:0:0:', 10, 20, 'test');
        $this->assertSame('1:0:19:12D:9:46:FFFF016A:0:0:0:', $timer->channel_id);
        $this->assertSame(10, $timer->time_begin);
        $this->assertSame(20, $timer->time_end);
        $this->assertSame('test', $timer->name);
    }

    public function testGetChannelId()
    {
        $timer = new timer($this->dreambox_ip, $this->channel_file);
        $channel_id = $timer->channel_id('NRK Super/NRK3');
        $this->assertSame('1:0:19:12D:9:46:FFFF016A:0:0:0:', $channel_id);
    }

    public function testGetChannelIdInvalid()
    {
        $this->expectException(DreamboxException::class);
        $this->expectExceptionMessage('Channel id not found');
        $timer = new timer($this->dreambox_ip, $this->channel_file);
        $timer->channel_id('Invalid');
    }

    public function testGetChannelIdReverse()
    {
        $timer = new timer($this->dreambox_ip, $this->channel_file);
        $channel_name = $timer->channel_id_reverse('1:0:19:12D:9:46:FFFF016A:0:0:0:');
        $this->assertSame('NRK Super/NRK3', $channel_name);
    }

    public function testGetChannelIdReverseInvalid()
    {
        $this->expectException(DreamboxException::class);
        $timer = new timer($this->dreambox_ip, $this->channel_file);
        $timer->channel_id_reverse('bad');
    }

    public function test_build()
    {
        $timer = new timer($this->dreambox_ip, $this->channel_file);
        $timer_obj = new objects\timer();
        $timer_obj->time_begin = strtotime('16:00');
        $timer_obj->time_end = strtotime('17:00');
        $timer_obj->channel_id = $timer->channel_id('NRK Super/NRK3');
        $timer_obj->name = 'test';

        $timer_array = $timer_obj->array();
        $this->assertFalse($timer_obj->disabled);
        $this->assertEquals(0, $timer_array['disabled']);
        $this->assertEquals(0, $timer_array['deleteOldOnSave']);
        $timer_obj->disabled = true;
        $this->assertEquals(1, $timer_obj->array()['disabled']);
    }

    public function testAdd_timer()
    {
        $timer = new timer($this->dreambox_ip, $this->channel_file);
        $start = strtotime('16:00');
        $end = strtotime('17:00');
        $channel_id = $timer->channel_id('NRK Super/NRK3');
        $response = $timer->add_timer($channel_id, $start, $end, 'test', 'test2');
        $this->assertEquals('Timer \'test\' added', $response);
    }

    public function testAdd_timer_HTTPError()
    {
        $timer = new timer($this->dreambox_ip, $this->channel_file);
        $this->expectException(DreamboxHTTPException::class);
        $timer->add_timer('1:0:19:EDE:E:46:FFFF019A:0:0:0:', 404, 1606061100, 'test');
    }

    public function testAdd_timer_InvalidChannel()
    {
        $timer = new timer($this->dreambox_ip, $this->channel_file);
        $this->expectException(InvalidArgumentException::class);
        $timer->add_timer('bad', 1, 2, 'test');
    }

    public function testAdd_timer_error()
    {
        $timer = new timer($this->dreambox_ip, $this->channel_file);
        $this->expectException(DreamboxException::class);
        $this->expectExceptionMessage('Conflicting Timer(s) detected!  / Vinterveiens helter / Grensevakten x10');
        $timer->add_timer('1:0:19:EDE:E:46:FFFF019A:0:0:0:', 1606056900, 1606061100, 'test');
    }

    public function testGetTimers()
    {
        $timer = new timer($this->dreambox_ip, $this->channel_file);
        $timers = $timer->get_timers();
        $this->assertIsArray($timers);
        $this->assertInstanceOf(objects\timer::class, $timers[0]);
        $this->assertSame('Nat Geo HD (N)', $timers[1]->channel_name);
        $this->assertIsArray($timers[1]->log_entries);
        $this->assertIsArray($timers[2]->log_entries[1]);
    }

    public function testHasTimer()
    {
        date_default_timezone_set('Europe/Oslo');
        $timer = new timer($this->dreambox_ip, $this->channel_file);
        $status = $timer->has_timer('Nat Geo HD (N)', strtotime('2020-11-16 03:55'), strtotime('2020-11-16 05:05'));
        $this->assertInstanceOf(objects\timer::class, $status);
    }

    public function testHasNotTimer()
    {
        $timer = new timer($this->dreambox_ip, $this->channel_file);
        $status = $timer->has_timer('Nat Geo HD (N)', strtotime('2020-11-16 00:55'), strtotime('2020-11-16 01:05'));
        $this->assertFalse($status);
    }

    public function testHasTimerOffset()
    {
        $timer = new timer($this->dreambox_ip, $this->channel_file);
        $timer->timers = [
            new objects\timer('1:0:19:EDE:E:46:FFFF019A:0:0:0:', strtotime('2021-11-06 23:00'), strtotime('2021-11-06 01:00')),
            new objects\timer('1:0:19:EDE:E:46:FFFF019A:0:0:0:', strtotime('2021-12-21 22:55'), strtotime('2021-12-22 01:05')),
        ];
        $status = $timer->has_timer('Nat Geo HD (N)', strtotime('2021-12-21 23:00'), strtotime('2021-12-22 01:00'));
        $this->assertSame($timer->timers[1], $status);
    }

    public function testHasTimerMidnight()
    {
        $timer = new timer($this->dreambox_ip, $this->channel_file);
        $timer->debug =true;
        $timer->timers = [
            new objects\timer('1:0:19:EDE:E:46:FFFF019A:0:0:0:', strtotime('2021-11-06 23:00'), strtotime('2021-11-06 01:00')),
            new objects\timer('1:0:19:EDE:E:46:FFFF019A:0:0:0:', strtotime('2021-12-21 23:00'), strtotime('2021-12-22 01:00')),
        ];
        $status = $timer->has_timer('Nat Geo HD (N)', strtotime('2021-12-21 23:00'), strtotime('2021-12-22 01:00'));
        $this->assertSame($timer->timers[1], $status);
    }

    public function testHasTimerDebug()
    {
        date_default_timezone_set('Europe/Oslo');
        $timer = new timer($this->dreambox_ip, $this->channel_file);
        $timer->debug = true;
        $timer->has_timer('Nat Geo HD (N)', strtotime('2020-11-16 03:55'), strtotime('2020-11-16 05:05'));
        $this->expectOutputRegex('/Recording start: 2020-11-16 03:55 program start: 2020-11-16 03:55\s+/');
    }

    public function testHasPartialTimer()
    {
        $timer = new timer($this->dreambox_ip, $this->channel_file);
        $timer->timers = [
            new objects\timer('1:0:19:EDE:E:46:FFFF019A:0:0:0:', strtotime('01:00'), strtotime('02:00')),
            new objects\timer('1:0:19:EDE:E:46:FFFF019A:0:0:0:', strtotime('09:00'), strtotime('11:00')),
            new objects\timer('1:0:19:EDE:E:46:FFFF019A:0:0:0:', strtotime('03:00'), strtotime('05:00')),
        ];

        $status = $timer->has_timer('Nat Geo HD (N)', strtotime('04:00'), strtotime('08:00'));
        $this->assertFalse($status);
        $status = $timer->has_timer('Nat Geo HD (N)', strtotime('04:00'), strtotime('08:00'), true);
        $this->assertInstanceOf(objects\timer::class, $status);
        $this->assertSame($timer->timers[2], $status);

        $status = $timer->has_timer('Nat Geo HD (N)', strtotime('08:00'), strtotime('10:00'), true);
        $this->assertInstanceOf(objects\timer::class, $status);
        $this->assertSame($timer->timers[1], $status);
    }

    public function testWrongXML()
    {
        $xml_file = files::path_join(__DIR__, 'emulator', 'data', 'epgnownext.xml');
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected root element e2timerlist, e2eventlist provided');
        objects\timer::parse(file_get_contents($xml_file));
    }

    public function testReplaceTimer()
    {
        $timer = new timer($this->dreambox_ip, $this->channel_file);

        $timer1 = new objects\timer();
        $timer1->time_begin = strtotime('08:00');
        $timer1->time_end = strtotime('09:00');
        $timer1->channel_id = '1:0:19:EDE:E:46:FFFF019A:0:0:0:';
        $timer1->name = 'short';

        $timer2 = new objects\timer();
        $timer2->time_begin = strtotime('08:00');
        $timer2->time_end = strtotime('09:30');
        $timer2->channel_id = '1:0:19:EDE:E:46:FFFF019A:0:0:0:';
        $timer2->name = 'merged';

        $timer->add_timer_obj($timer1);
        $this->assertContains($timer1, $timer->timers);
        $this->assertNotContains($timer2, $timer->timers);
        $timer_check = $timer->has_timer('Nat Geo HD (N)', $timer1->time_begin, $timer1->time_end);
        $this->assertSame($timer1, $timer_check);
        $timer_check = $timer->has_timer('Nat Geo HD (N)', $timer1->time_begin, $timer2->time_end);
        $this->assertFalse($timer_check);

        $timer->replace_timer($timer1, $timer2);
        $this->assertContains($timer2, $timer->timers);
        $this->assertNotContains($timer1, $timer->timers);
        $timer_check = $timer->has_timer('Nat Geo HD (N)', $timer2->time_begin, $timer2->time_end);
        $this->assertSame($timer2, $timer_check);
    }

    public function testDeleteTimer()
    {
        $timer = new timer($this->dreambox_ip, $this->channel_file);

        $timer1 = new objects\timer();
        $timer1->time_begin = strtotime('08:00');
        $timer1->time_end = strtotime('09:00');
        $timer1->channel_id = '1:0:19:EDE:E:46:FFFF019A:0:0:0:';
        $timer1->name = 'bad';

        $timer->add_timer_obj($timer1);
        $this->assertContains($timer1, $timer->timers);
        $response = $timer->delete_timer($timer1);
        $this->assertEquals("The timer 'bad' has been deleted successfully", $response);
        $this->assertNotContains($timer1, $timer->timers);
    }
}
