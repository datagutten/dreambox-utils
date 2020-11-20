<?php


namespace datagutten\dreambox\web\objects;


use SimpleXMLElement;

class result extends XMLData
{
    //e2simplexmlresult
    /**
     * @var bool
     */
    public $state;
    /**
     * @var string
     */
    public $state_text;

    function __construct(SimpleXMLElement $xml)
    {
        parent::__construct($xml, 'e2simplexmlresult');
        $this->state = $this->bool('e2state');
        $this->state_text = $this->string('e2statetext');
    }

    /**
     * Parse event list XML
     * @param string $xml XML string with root tag e2simplexmlresult
     * @return self
     */
    public static function parse_string(string $xml)
    {
        $xml = simplexml_load_string($xml);
        return new self($xml);
    }
}