<?php


namespace datagutten\dreambox\web\objects;


use InvalidArgumentException;

class timer
{
    public static array $after_event_text = [0 => 'Do nothing', 1 => 'Standby', 2 => 'Shutdown', 3 => 'Auto'];
    /**
     * @var string Dreambox channel id
     */
    public string $channel_id;
    /**
     * @var string
     */
    public string $channel_name;
    /**
     * @var bool
     */
    public bool $eit = false;
    /**
     * @var string
     */
    public string $name;
    /**
     * @var string Timer description
     */
    public string $description = '';
    /**
     * @var string
     */
    public string $description_extended;
    /**
     * @var bool
     */
    public bool $disabled = false;
    /**
     * @var int Timer start timestamp
     */
    public int $time_begin;
    /**
     * @var int Timer start, alias for time_begin
     */
    public int $start;
    /**
     * @var int Timer end timestamp
     */
    public int $time_end;
    /**
     * @var int Timer end, alias for time_end
     */
    public $end;
    /**
     * @var int Timer duration
     */
    public int $duration;
    /**
     * @var int
     */
    public int $start_prepare;
    /**
     * @var bool
     */
    public bool $just_play;
    /**
     * @var int What to do after event
     */
    public int $after_event = 3; //Auto
    /**
     * @var string Recording path
     */
    public string $location = '/media/hdd/movie/';
    /**
     * @var string
     */
    public string $tags = '';
    /**
     * @var string
     */
    public string $log_entries;
    /**
     * @var string
     */
    public string $file_name;
    /**
     * @var string
     */
    public string $back_off;
    /**
     * @var int
     */
    public int $next_activation;
    /**
     * @var bool
     */
    public bool $first_try_prepare;
    /**
     * @var int
     */
    public int $state;
    /**
     * @var int
     */
    public int $repeated = 0;
    /**
     * @var bool
     */
    public bool $dont_save;
    /**
     * @var bool
     */
    public bool $canceled;

    /**
     * @var bool Delete old timers on save
     */
    public bool $delete_old = false;
    public int $sessionId = 0;

    public function __construct($channel_id = '', int $start = 0, int $end = 0, string $name = '')
    {
        if (!empty($channel_id))
            $this->channel_id = $channel_id;
        if (!empty($start))
            $this->time_begin = $start;
        if (!empty($end))
            $this->time_end = $end;
        if (!empty($name))
            $this->name = $name;
    }

    /**
     * Parse timer list XML
     * @param string $xml XML string with root element e2timerlist
     * @return self[]
     */
    public static function parse(string $xml): array
    {
        return XMLData::parse_string($xml, 'e2timerlist', function ($timer_xml)
        {
            $timer = new TimerXML($timer_xml);
            return $timer->timer;
        });
    }

    public function array(): array
    {
        if (!preg_match('/[0-9A-F:]{30}/', $this->channel_id))
            throw new InvalidArgumentException('Invalid channel id: ' . $this->channel_id);

        return [
            'sRef' => $this->channel_id,
            'begin' => $this->time_begin,
            'end' => $this->time_end,
            'name' => $this->name,
            'description' => $this->description,
            'dirname' => $this->location,
            'tags' => $this->tags,
            'afterevent' => $this->after_event,
            'eit' => $this->eit ? 1 : 0,
            'disabled' => $this->disabled ? 1 : 0,
            'repeated' => $this->repeated,
            'deleteOldOnSave' => $this->delete_old ? 1 : 0,
            'sessionId' => $this->sessionId
        ];
    }

    /**
     * Merge the current timer with an overlapping timer
     * @param timer $timer Timer to merge
     * @return timer Merged timer
     */
    public function merge(timer $timer): timer
    {
        if ($this->time_begin < $timer->time_begin)
        {
            $first = $this;
            $last = $timer;
        }
        else
        {
            $first = $timer;
            $last = $this;
        }

        if ($first->time_end < $last->time_begin)
            throw new InvalidArgumentException('Timers does not overlap');
        if ($first->channel_id != $last->channel_id)
            throw new InvalidArgumentException('Timers are from different channels');
        $merged = clone($first);

        $merged->time_end = $last->time_end;
        return $merged;
    }

    public static function from_event(event $event, $margin = 0): timer
    {
        $timer = new static();
        $timer->time_begin = $event->start - $margin;
        $timer->time_end = $event->start + $event->duration + $margin;
        $timer->name = $event->title;
        $timer->description = $event->description;
        $timer->description_extended = $event->description_extended;
        $timer->channel_id = $event->service_reference;
        $timer->channel_name = $event->service_name;
        return $timer;
    }
}