<?php


namespace datagutten\dreambox\web\objects;


use RuntimeException;
use SimpleXMLElement;

class result extends XMLData
{
    /**
     * @var bool
     */
    public bool $state;
    /**
     * @var string
     */
    public string $state_text;

    function __construct(SimpleXMLElement $xml)
    {
        parent::__construct($xml);
        self::validate_element($xml, 'e2simplexmlresult');
        $this->state = $this->bool('e2state');
        $this->state_text = $this->string('e2statetext');
    }

    /**
     * Parse result XML
     * @param string $xml_string XML string with root tag e2simplexmlresult
     * @return self
     */
    public static function parse(string $xml_string): result
    {
        $xml = simplexml_load_string($xml_string);
        if($xml===false)
            throw new RuntimeException(sprintf('Error parsing XML: "%s"', $xml_string));
        self::validate_element($xml, 'e2simplexmlresult');
        return new self($xml);
    }
}