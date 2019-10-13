<?php


namespace datagutten\dreambox\web;


use InvalidArgumentException;
use Requests;
use Requests_Exception;

class common
{
    public $dreambox_ip;

    /**
     * common constructor.
     * @param string $dreambox_ip IP to dreambox
     * @throws Requests_Exception Unable to connect to dreambox
     */
    function __construct($dreambox_ip)
    {
        if(empty($dreambox_ip))
            throw new InvalidArgumentException('Dreambox IP empty');
        $this->dreambox_ip = $dreambox_ip;
        $response = Requests::head('http://'.$dreambox_ip);
        $response->throw_for_status();
    }

    function channel_file()
    {
        return sprintf('%s/channels_%s.json',  __DIR__, $this->dreambox_ip);
    }
}