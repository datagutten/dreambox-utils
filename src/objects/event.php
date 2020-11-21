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
     * @var int
     */
    public $start;
    /**
     * @var int
     */
    public $duration;
    /**
     * @var int
     */
    public $current_time;
    /**
     * @var string
     */
    public $title;
    /**
     * @var string
     */
    public $description;
    /**
     * @var string
     */
    public $description_extended;
    /**
     * @var string
     */
    public $service_reference;
    /**
     * @var string
     */
    public $service_name;

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
    public static function parse(string $xml)
    {
        return parent::parse_string($xml, 'e2eventlist', self::class);
    }
}