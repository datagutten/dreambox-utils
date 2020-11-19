<?php


namespace datagutten\dreambox\web;


use datagutten\dreambox\web\exceptions\DreamboxHTTPException;
use SimpleXMLElement;

class epg extends common
{
    public $channels;

    /**
     * @param $channel
     * @return array|SimpleXMLElement
     * @throws DreamboxHTTPException
     */
    public function epg($channel)
    {
        $channel_id = array_search($channel, $this->channels);
        if ($channel_id === false)
            return [];
        $data = array('sRef' => $channel_id, 'sessionid' => '0');
        $response = $this->session->post('web/epgservice', ['Cache-Control'=>'no-cache,no-store'], $data);
        if(!$response->success)
            throw new DreamboxHTTPException($response);

        return simplexml_load_string($response->body);
    }

    /**
     * Get all channels
     * @return SimpleXMLElement
     * @throws DreamboxHTTPException
     */
    public function channels()
    {
        $data = 'bRef=1:7:1:0:0:0:0:0:0:0:(type == 1) || (type == 17) || (type == 22) || (type == 195) || (type == 25) ORDER BY name&sessionid=0';
        $response = $this->session->post('web/epgnownext', [], $data);
        if(!$response->success)
            throw new DreamboxHTTPException($response);
        return simplexml_load_string($response->body);
    }

    /**
     * @return array Array with channel id as key and name as value
     * @throws DreamboxHTTPException
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
     * Save channel list to a JSON file
     * @param string $file File to save the channels to
     * @throws DreamboxHTTPException
     */
    public function save_channel_list(string $file)
    {
        file_put_contents($file, json_encode($this->channel_list()));
    }
}