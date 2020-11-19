<?php


namespace datagutten\dreambox\web;


use Exception;
use FileNotFoundException;
use InvalidArgumentException;
use Requests_Exception;
use Requests_Exception_HTTP;
use SimpleXMLElement;

class timer extends common
{
    public $channels;

    /**
     * timer constructor.
     * @param $dreambox_ip
     * @throws FileNotFoundException Channel list file not found
     * @throws Requests_Exception
     * @throws Exception Channel list file empty
     */
    function __construct($dreambox_ip)
    {
        parent::__construct($dreambox_ip);
        $file = $this->channel_file();
        if(!file_exists($file))
            throw new FileNotFoundException($file);
        $data = file_get_contents($file);
        $this->channels = json_decode($data, true);
        if(empty($this->channels))
            throw new Exception('Channel list not found');
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
     * @param string $channel_name Channel name
     * @throws Exception Channel id not found
     * @return string Channel id
     */
    function channel_id(string $channel_name)
    {
        $channel_id = array_search($channel_name, $this->channels);
        if ($channel_id === false)
            throw new Exception(sprintf('Channel id not found for %s', $channel_name));
        else
            return $channel_id;
    }

    /**
     * @param string $channel_id Channel id
     * @return mixed
     * @throws Exception
     */
    function channel_id_reverse(string $channel_id)
    {
        if(!isset($this->channels[$channel_id]))
            throw new Exception('No channel found for id '.$channel_id);
        else
            return $this->channels[$channel_id];
    }

    /**
     * @param string $channel_id Dreambox channel id
     * @param int $begin Recording start timestamp
     * @param int $end Recording end timestamp
     * @param string $name Recording name
     * @param string $description Recording description
     * @return string Response from dreambox
     * @throws Requests_Exception
     */
    public function add_timer(string $channel_id, int $begin, int $end, string $name, $description='')
    {
        if(!preg_match('/[0-9A-F:]{30}/', $channel_id))
            throw new InvalidArgumentException('Invalid channel id: '.$channel_id);
        $timer = $this->timer_template;
        $timer['sRef'] = $channel_id;
        $timer['begin'] = $begin;
        $timer['end'] = $end;
        $timer['name'] = $name;
        if(!empty($description))
            $timer['description'] = $description;

        $response = $this->session->post('web/timerchange', [], $timer);
        $response->throw_for_status();
        $xml = simplexml_load_string($response->body);
        //$status = $xml->{'e2state'};

        return $xml->{'e2statetext'};
    }

    /**
     * @return SimpleXMLElement
     * @throws Requests_Exception
     * @throws Requests_Exception_HTTP
     */
    public function get_timers()
    {
        $response = $this->session->get('web/timerlist');
        $response->throw_for_status();
        return simplexml_load_string($response->body);
    }
}