<?php


namespace datagutten\dreambox\web\objects;


use SimpleXMLElement;

class event extends XMLData
{
    /**
     * @var int
     */
    public $id;
    /**
     * @var int Event start time as unix timestamp
     */
    public int $start;
    /**
     * @var int Event duration in seconds
     */
    public int $duration;
    /**
     * @var int
     */
    public int $current_time;
    /**
     * @var string Event title
     */
    public string $title;
    /**
     * @var string Event description
     */
    public string $description;
    /**
     * @var string
     */
    public string $description_extended;
    /**
     * @var string Channel id
     */
    public string $service_reference;
    /**
     * @var string Channel name
     */
    public string $service_name;

    public function __construct(SimpleXMLElement $xml)
    {
        parent::__construct($xml);
        self::validate_element($xml, 'e2event');

        $this->id = $this->int('e2eventid');
        $this->start = $this->int('e2eventstart');
        $this->duration = $this->int('e2eventduration');
        $this->current_time = $this->int('e2currenttime');
        $this->title = $this->string('e2eventtitle');
        $this->description = $this->string('e2eventdescription');
        $this->description_extended = $this->string('e2eventdescriptionextended');
        $this->service_reference = $this->string('e2eventservicereference');
        $this->service_name = $this->string('e2eventservicename');
    }

    /**
     * Parse event list XML
     * @param string $xml XML string with root element e2eventlist
     * @return self[]
     */
    public static function parse(string $xml): array
    {
        return parent::parse_string($xml, 'e2eventlist');
    }
}