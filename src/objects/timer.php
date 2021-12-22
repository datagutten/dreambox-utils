<?php


namespace datagutten\dreambox\web\objects;


class timer
{
    public $xml;
    /**
     * @var string
     */
    public $channel_id;
    /**
     * @var string
     */
    public $channel_name;
    /**
     * @var string
     */
    public $eit;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $description;
    /**
     * @var string
     */
    public $description_extended;
    /**
     * @var bool
     */
    public $disabled;
    /**
     * @var int Timer start
     */
    public $time_begin;
    /**
     * @var int Timer start, alias for time_begin
     */
    public $start;
    /**
     * @var int Timer end
     */
    public $time_end;
    /**
     * @var int Timer end, alias for time_end
     */
    public $end;
    /**
     * @var int
     */
    public $duration;
    /**
     * @var int
     */
    public $start_prepare;
    /**
     * @var bool
     */
    public $just_play;
    /**
     * @var int
     */
    public $after_event;
    /**
     * @var string
     */
    public $location;
    /**
     * @var string
     */
    public $tags;
    /**
     * @var string
     */
    public $log_entries;
    /**
     * @var string
     */
    public $file_name;
    /**
     * @var string
     */
    public $back_off;
    /**
     * @var int
     */
    public $next_activation;
    /**
     * @var bool
     */
    public $first_try_prepare;
    /**
     * @var int
     */
    public $state;
    /**
     * @var int
     */
    public $repeated;
    /**
     * @var bool
     */
    public $dont_save;
    /**
     * @var bool
     */
    public $canceled;

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