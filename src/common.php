<?php


namespace datagutten\dreambox\web;


use Exception;
use InvalidArgumentException;
use Requests_Exception;
use Requests_Session;

class common
{
    public $dreambox_ip;
    /**
     * @var Requests_Session
     */
    public $session;

    /**
     * common constructor.
     * @param string $dreambox_ip IP to dreambox
     * @throws Requests_Exception Unable to connect to dreambox
     * @throws Exception Dreambox not found
     */
    function __construct(string $dreambox_ip)
    {
        if(empty($dreambox_ip))
            throw new InvalidArgumentException('Dreambox IP empty');
        $this->dreambox_ip = $dreambox_ip;
        $this->session = new Requests_Session('http://'.$dreambox_ip.'/');
        $response = $this->session->get('');
        $response->throw_for_status();
        if(strpos($response->body, 'Dreambox WebControl')===false)
            throw new Exception(sprintf('Dreambox not found at %s', $response->url));
    }
}