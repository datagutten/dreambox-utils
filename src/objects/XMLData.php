<?php


namespace datagutten\dreambox\web\objects;


use InvalidArgumentException;
use SimpleXMLElement;

class XMLData
{
    /**
     * @var SimpleXMLElement
     */
    public SimpleXMLElement $xml;

    public function __construct(SimpleXMLElement $xml)
    {
        $this->xml = $xml;
    }

    protected function string($tag): string
    {
        return (string)$this->xml->$tag;
    }

    protected function int($tag): int
    {
        return (int)$this->xml->$tag;
    }

    protected function bool($tag): bool
    {
        return $this->xml->$tag == '1' || $this->xml->$tag == 'True';
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
     * @param ?callable $callback Pass each element to this function
     * @return XMLData[]
     */
    public static function parse_string(string $xml, string $root_element, callable $callback=null): array
    {
        $xml = simplexml_load_string($xml);
        self::validate_element($xml, $root_element);

        $events = [];
        foreach ($xml->children() as $child)
        {
            if (!empty($callback))
                $events[] = $callback($child);
            else
                $events[] = new static($child);
        }
        return $events;
    }
}