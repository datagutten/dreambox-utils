<?php


namespace datagutten\dreambox\web\objects;


use InvalidArgumentException;
use SimpleXMLElement;

class XMLData
{
    /**
     * @var SimpleXMLElement
     */
    public $xml;

    public function __construct(SimpleXMLElement $xml)
    {
        $this->xml = $xml;
    }

    public function string($tag)
    {
        return (string)$this->xml->$tag;
    }

    public function int($tag)
    {
        return (int)$this->xml->$tag;
    }

    public function bool($tag)
    {
        return $this->xml->$tag === '1' || $this->xml->$tag == 'True';
    }

    protected static function validate_element(SimpleXMLElement $xml, $element)
    {
        if(!empty($element) && $xml->getName()!=$element)
            throw new InvalidArgumentException(sprintf('Expected root element %s, %s provided', $element, $xml->getName()));
    }

    /**
     * Create objects from XML string
     * @param string $xml XML string
     * @param string $root_element XML root element to be validated
     * @param string $class Class to use
     * @return array
     */
    protected static function parse_string(string $xml, string $root_element, string $class)
    {
        $xml = simplexml_load_string($xml);
        self::validate_element($xml, $root_element);

        $events = [];
        foreach($xml->children() as $child)
        {
            $events[] = new $class($child);
        }
        return $events;
    }
}