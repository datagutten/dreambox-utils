<?php


use PHPUnit\Framework\TestCase;

class TimerMergeTest extends TestCase
{
    public function testMerge()
    {
        $timer1 = new datagutten\dreambox\web\objects\timer();
        $timer1->time_begin = strtotime('08:00');
        $timer1->time_end = strtotime('09:00');
        $timer1->channel_id = '1:0:19:EDE:E:46:FFFF019A:0:0:0:';

        $timer2 = new datagutten\dreambox\web\objects\timer();
        $timer2->time_begin = strtotime('08:30');
        $timer2->time_end = strtotime('09:30');
        $timer2->channel_id = '1:0:19:EDE:E:46:FFFF019A:0:0:0:';

        $merged = $timer1->merge($timer2);
        $this->assertEquals(strtotime('08:00'), $merged->time_begin);
        $this->assertEquals(strtotime('09:30'), $merged->time_end);
        $this->assertNotSame($merged, $timer1);
        $this->assertNotSame($merged, $timer2);
    }

    public function testMergeReverse()
    {
        $timer1 = new datagutten\dreambox\web\objects\timer();
        $timer1->time_begin = strtotime('08:00');
        $timer1->time_end = strtotime('09:00');
        $timer1->channel_id = '1:0:19:EDE:E:46:FFFF019A:0:0:0:';

        $timer2 = new datagutten\dreambox\web\objects\timer();
        $timer2->time_begin = strtotime('08:30');
        $timer2->time_end = strtotime('09:30');
        $timer2->channel_id = '1:0:19:EDE:E:46:FFFF019A:0:0:0:';

        $merged = $timer2->merge($timer1);
        $this->assertEquals(strtotime('08:00'), $merged->time_begin);
        $this->assertEquals(strtotime('09:30'), $merged->time_end);
        $this->assertNotSame($merged, $timer1);
        $this->assertNotSame($merged, $timer2);
    }

    public function testMergeDifferentChannel()
    {
        $timer1 = new datagutten\dreambox\web\objects\timer();
        $timer1->time_begin = strtotime('08:00');
        $timer1->time_end = strtotime('09:00');
        $timer1->channel_id = '1:0:19:EDE:E:46:FFFF019A:0:0:0:';

        $timer2 = new datagutten\dreambox\web\objects\timer();
        $timer2->time_begin = strtotime('08:30');
        $timer2->time_end = strtotime('09:30');
        $timer2->channel_id = '1:0:19:EDE:E:46:FFFF019A:0:0:1:';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Timers are from different channels');
        $timer2->merge($timer1);
    }

    public function testMergeDifferentTime()
    {
        $timer1 = new datagutten\dreambox\web\objects\timer();
        $timer1->time_begin = strtotime('08:00');
        $timer1->time_end = strtotime('09:00');
        $timer1->channel_id = '1:0:19:EDE:E:46:FFFF019A:0:0:0:';

        $timer2 = new datagutten\dreambox\web\objects\timer();
        $timer2->time_begin = strtotime('09:50');
        $timer2->time_end = strtotime('10:20');
        $timer2->channel_id = '1:0:19:EDE:E:46:FFFF019A:0:0:1:';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Timers does not overlap');
        $timer2->merge($timer1);
    }
}
