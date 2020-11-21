<?php


namespace datagutten\dreambox\web\objects;


use SimpleXMLElement;

class timer extends XMLData
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
     * @var int
     */
    public $time_begin;
    /**
     * @var int
     */
    public $time_end;
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
     * timer constructor.
     * @param SimpleXMLElement $timer_xml SimpleXMLElement of a single timer
     */
    public function __construct(SimpleXMLElement $timer_xml)
    {
        parent::__construct($timer_xml);
        self::validate_element($timer_xml, 'e2timer');

        $this->channel_id = (string)$this->xml->{'e2servicereference'};
        $this->channel_name = (string)$this->xml->{'e2servicename'};
        $this->eit = $this->string('e2eit');
        $this->name = $this->string('e2name');
        $this->description = $this->string('e2description');
        $this->description_extended = $this->string('e2descriptionextended');
        $this->disabled = $this->bool('e2disabled');
        $this->time_begin = $this->int('e2timebegin');
        $this->time_end = $this->int('e2timeend');
        $this->duration = $this->int('e2duration');
        $this->start_prepare = $this->int('e2startprepare');
        $this->just_play = $this->bool('e2justplay');
        $this->after_event = $this->int('e2afterevent');
        $this->location = $this->string('e2location');
        $this->tags = $this->string('e2tags');
        $this->log_entries = $this->string('e2logentries');
        $this->file_name = $this->string('e2filename');
        $this->back_off = $this->string('e2backoff');
        $this->next_activation = $this->int('e2nextactivation');
        $this->first_try_prepare = $this->bool('e2firsttryprepare');
        $this->state = $this->int('e2state');
        $this->repeated = $this->int('e2repeated');
        $this->dont_save = $this->bool('e2dontsave');
        $this->canceled = $this->bool('e2cancled');
    }

    /**
     * Parse timer list XML
     * @param string $xml XML string with root element e2timerlist
     * @return self[]
     */
    public static function parse(string $xml)
    {
        return parent::parse_string($xml, 'e2timerlist', self::class);
    }
}