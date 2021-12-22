<?php


namespace datagutten\dreambox\web\objects;


class timer
{
    public static array $after_event_text = [0 => 'Do nothing', 1 => 'Standby', 2 => 'Shutdown', 3 => 'Auto'];
    /**
     * @var string
     */
    public string $channel_id;
    /**
     * @var string
     */
    public string $channel_name;
    /**
     * @var string
     */
    public string $eit;
    /**
     * @var string
     */
    public string $name;
    /**
     * @var string
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
     * @var int Timer start
     */
    public int $time_begin;
    /**
     * @var int Timer start, alias for time_begin
     */
    public int $start;
    /**
     * @var int Timer end
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
    public string $tags;
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
}