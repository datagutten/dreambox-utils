<?php


namespace datagutten\dreambox\web\objects;


use RuntimeException;
use SimpleXMLElement;

class result extends XMLData
{
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
        parent::__construct($xml);
        self::validate_element($xml, 'e2simplexmlresult');
        $this->state = $this->bool('e2state');
        $this->state_text = $this->string('e2statetext');
    }

    /**
     * Parse result XML
     * @param string $xml XML string with root tag e2simplexmlresult
     * @return self
     */
    public static function parse(string $xml): result
    {
        $xml = simplexml_load_string($xml);
        if($xml===false)
            throw new RuntimeException(sprintf('Error parsing XML: "%s"', $xml));
        self::validate_element($xml, 'e2simplexmlresult');
        return new self($xml);
    }
}