<?php


namespace datagutten\dreambox\web;


use Requests;
use Requests_Exception;
use SimpleXMLElement;

class epg extends common
{
    public $channels;

    /**
     * @param $channel
     * @return array|SimpleXMLElement
     * @throws Requests_Exception
     */
    public function epg($channel)
    {
        $channel_id = array_search($channel, $this->channels);
        if ($channel_id === false)
            return [];
        $data = array('sRef' => $channel_id, 'sessionid' => '0');
        $response = Requests::post(sprintf('http://%s/web/epgservice', $this->dreambox_ip), ['Cache-Control'=>'no-cache,no-store'], $data);
        $response->throw_for_status();

        return simplexml_load_string($response->body);
    }

    /**
     * Get all channels
     * @return SimpleXMLElement
     * @throws Requests_Exception
     */
    public function channels()
    {
        $data = 'bRef=1:7:1:0:0:0:0:0:0:0:(type == 1) || (type == 17) || (type == 22) || (type == 195) || (type == 25) ORDER BY name&sessionid=0';
        $response = Requests::post(sprintf('http://%s/web/epgnownext', $this->dreambox_ip), [], $data);
        $response->throw_for_status();
        return simplexml_load_string($response->body);
    }

    /**
     * @return array Array with channel id as key and name as value
     * @throws Requests_Exception
     */
    public function channel_list()
    {
        $channels = [];
        foreach($this->channels()->{'e2event'} as $channel)
        {
            $key = (string)$channel->{'e2eventservicereference'};
            if(isset($channels[$key]))
                continue;
            $channels[$key] = (string)$channel->{'e2eventservicename'};
        }
        return $channels;
    }

    /**
     * Save channel list to file
     * @throws Requests_Exception
     */
    public function save_channel_list()
    {
        $file = $this->channel_file();
        file_put_contents($file, json_encode($this->channel_list()));
    }
}