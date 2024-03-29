<?php /** @noinspection PhpUnhandledExceptionInspection */

namespace datagutten\dreambox\web_tests;

use datagutten\dreambox\web\epg;
use datagutten\dreambox\web\objects;
use datagutten\tools\files\files;
use SimpleXMLElement;

class epgTest extends DreamboxTestCase
{
    public function testEpg()
    {
        $epg = new epg($this->dreambox_ip);
        $epg_events = $epg->epg('1:0:19:EDE:E:46:FFFF019A:0:0:0:');
        $this->assertIsArray($epg_events);
        $this->assertInstanceOf(objects\event::class, $epg_events[1]);
        $this->assertEquals('Supercar Megabuild', $epg_events[11]->title);
    }

    public function testEpg_channel_name()
    {
        $channel_file = files::path_join(__DIR__, 'test_channels_127.0.0.1.json');
        $epg = new epg($this->dreambox_ip);
        $epg_events = $epg->epg_channel_name('Nat Geo HD (N)', $channel_file);
        $this->assertIsArray($epg_events);
        $this->assertInstanceOf(objects\event::class, $epg_events[1]);
        $this->assertEquals('Supercar Megabuild', $epg_events[11]->title);
    }

    public function testSave_channel_list()
    {
        $channel_file = tempnam(sys_get_temp_dir(), 'channels');
        $epg = new epg($this->dreambox_ip);
        $this->assertSame(0, filesize($channel_file));
        $epg->save_channel_list($channel_file);
        clearstatcache();
        $this->assertGreaterThan(0, filesize($channel_file));
    }

    public function testSearch()
    {
        $epg = new epg($this->dreambox_ip);
        $results = $epg->search('vinterveien');
        $this->assertIsArray($results);
        $this->assertInstanceOf(objects\event::class, $results[1]);
        $this->assertSame('Vinterveiens helter', $results[1]->title);
    }

    public function testChannels()
    {
        $epg = new epg($this->dreambox_ip);
        $channels = $epg->channels();
        $this->assertInstanceOf(SimpleXMLElement::class, $channels);
        $events = iterator_to_array($channels->{'e2event'}, false);
        $this->assertEquals('BBC World News', $events[3]->{'e2eventservicename'});
    }

    public function testChannel_list()
    {
        $epg = new epg($this->dreambox_ip);
        $channels = $epg->channel_list();
        $this->assertArrayHasKey('1:0:1:26F:5:46:FFFF0122:0:0:0:', $channels);
        $this->assertSame('Cartoon Network', $channels['1:0:1:26F:5:46:FFFF0122:0:0:0:']);
    }

    public function testTimerFromEvent()
    {
        $epg = new epg($this->dreambox_ip);
        $epg_events = $epg->epg('1:0:19:EDE:E:46:FFFF019A:0:0:0:');
        $this->assertIsArray($epg_events);
        $event = $epg_events[11];
        $this->assertInstanceOf(objects\event::class, $event);
        $this->assertEquals('Supercar Megabuild', $event->title);
        $timer = objects\timer::from_event($event);
        $this->assertInstanceOf(objects\timer::class, $timer);
        $this->assertEquals($event->start, $timer->time_begin);
        $this->assertEquals($event->start + $event->duration, $timer->time_end);
    }
}
