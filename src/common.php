<?php


namespace datagutten\dreambox\web;


use datagutten\dreambox\web\exceptions\DreamboxException;
use datagutten\dreambox\web\exceptions\DreamboxHTTPException;
use FileNotFoundException;
use InvalidArgumentException;
use WpOrg\Requests;

class common
{
    public string $dreambox_ip;

    /**
     * @var Requests\Session
     */
    public Requests\Session $session;

    /**
     * @var array Array with channel names as values and dreambox channel ids as keys
     */
    public array $channels;

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
        $this->session = new Requests\Session('http://'.$dreambox_ip.'/');
        $response = $this->session->get('');
        if(!$response->success)
            throw new DreamboxHTTPException($response);

        if(strpos($response->body, 'Dreambox WebControl')===false)
            throw new DreamboxException(sprintf('Dreambox not found at %s', $response->url));
    }

    /**
     * Load channel list from JSON
     * @param string $channel_file Channel file generated by save_channels.php
     * @throws FileNotFoundException Channel list file not found
     * @throws DreamboxException Channel list file empty
     */
    function load_channel_list(string $channel_file)
    {
        if(!file_exists($channel_file))
            throw new FileNotFoundException($channel_file);
        $data = file_get_contents($channel_file);
        $this->channels = json_decode($data, true);
        if(empty($this->channels))
            throw new DreamboxException('Channel list empty');
    }
}