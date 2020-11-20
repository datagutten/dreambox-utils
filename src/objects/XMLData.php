<?php


namespace datagutten\dreambox\web\objects;


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
        return $this->xml->$tag === '1';
    }
}