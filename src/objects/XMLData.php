<?php


namespace datagutten\dreambox\web\objects;


use SimpleXMLElement;

class XMLData
{
    /**
     * @var SimpleXMLElement
     */
    public $xml;

    public function __construct(SimpleXMLElement $xml, string $root_element = '')
    {
        if(!empty($root_element) && $xml->getName()!=$root_element)
            throw new \InvalidArgumentException(printf('Expected root element %s, %s provided', $root_element, $xml->getName()));
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
}