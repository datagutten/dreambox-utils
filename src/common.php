<?php


namespace datagutten\dreambox\web;


use datagutten\dreambox\web\exceptions\DreamboxException;
use datagutten\dreambox\web\exceptions\DreamboxHTTPException;
use InvalidArgumentException;
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
     * @throws DreamboxHTTPException Unable to connect to dreambox
     * @throws DreamboxException Dreambox not found
     */
    function __construct(string $dreambox_ip)
    {
        if(empty($dreambox_ip))
            throw new InvalidArgumentException('Dreambox IP empty');
        $this->dreambox_ip = $dreambox_ip;
        $this->session = new Requests_Session('http://'.$dreambox_ip.'/');
        $response = $this->session->get('');
        if(!$response->success)
            throw new DreamboxHTTPException($response);

        if(strpos($response->body, 'Dreambox WebControl')===false)
            throw new DreamboxException(sprintf('Dreambox not found at %s', $response->url));
    }
}