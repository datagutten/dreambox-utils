<?php


namespace datagutten\dreambox\web;


use FileNotFoundException;
use Requests;
use Requests_Exception;

class timer extends common
{
    public $channels;

    /**
     * timer constructor.
     * @param $dreambox_ip
     * @throws FileNotFoundException
     * @throws Requests_Exception
     */
    function __construct($dreambox_ip)
    {
        parent::__construct($dreambox_ip);
        $file = $this->channel_file();
        if(!file_exists($file))
            throw new FileNotFoundException($file);
        $this->channels = json_decode($file);
    }

    public $timer_template=array(
        'sRef'=>false,
        'begin'=>false,
        'end'=>false,
        'name'=>false,
        'description'=>'',
        'dirname'=>'/media/hdd/movie/',
        'tags'=>'',
        'afterevent'=>'3',
        'eit'=>'0',
        'disabled'=>'0',
        'justplay'=>'0',
        'repeated'=>'0',
        'deleteOldOnSave'=>'0',
        'sessionid'=>'0');

    /**
     * @param string $channel Dreambox channel id
     * @param int $begin Recording start timestamp
     * @param int $end Recording end timestamp
     * @param string $name
     * @return string Response from dreambox
     * @throws Requests_Exception
     */
    public function add_timer($channel, $begin, $end, $name)
    {
        $channel_id = array_search($channel, $this->channels);
        if ($channel_id === false)
            return null;
        $timer = $this->timer_template;
        $timer['sRef'] = $channel_id;
        $timer['begin'] = $begin;
        $timer['end'] = $end;
        $timer['name'] = $name;
        $response = Requests::post(sprintf('http://%s/web/timerchange', $this->dreambox_ip), [], $timer);
        $response->throw_for_status();
        return $response->body;
    }
}