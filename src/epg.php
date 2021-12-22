<?php


namespace datagutten\dreambox\web;


use datagutten\dreambox\web\exceptions\DreamboxException;
use datagutten\dreambox\web\exceptions\DreamboxHTTPException;
use datagutten\dreambox\web\objects;
use FileNotFoundException;
use InvalidArgumentException;
use SimpleXMLElement;

class epg extends common
{
    /**
     * Get EPG for channel
     * @param string $channel_id Channel ID
     * @return objects\event[] Array with event objects
     * @throws DreamboxHTTPException
     */
    public function epg(string $channel_id): array
    {
        $data = array('sRef' => $channel_id, 'sessionid' => '0');
        $response = $this->session->post('web/epgservice', ['Cache-Control'=>'no-cache,no-store'], $data);
        if(!$response->success)
            throw new DreamboxHTTPException($response);

        return objects\event::parse($response->body);
    }

    /**
     * @param string $channel_name Channel name
     * @param string $channel_file Channel file generated by save_channels.php
     * @throws DreamboxException Channel list file empty
     * @throws FileNotFoundException Channel list file not found
     * @return objects\event[] Array with event objects
     */
    public function epg_channel_name(string $channel_name, string $channel_file): array
    {
        $this->load_channel_list($channel_file);
        $channel_id = array_search($channel_name, $this->channels);
        if ($channel_id === false)
            throw new InvalidArgumentException(sprintf('No channel with name %s', $channel_name));
        return $this->epg($channel_id);
    }

    /**
     * Get all channels
     * @return SimpleXMLElement
     * @throws DreamboxHTTPException
     */
    public function channels(): SimpleXMLElement
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
    public function channel_list(): array
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

    /**
     * Search for events in EPG
     * @param string $query Search query
     * @return objects\event[] Array with event objects
     * @throws DreamboxHTTPException
     */
    public function search(string $query): array
    {
        $response = $this->session->post('web/epgsearch', [], ['search'=>$query, 'sessionid'=>'0']);
        if(!$response->success)
            throw new DreamboxHTTPException($response);
        return objects\event::parse($response->body);
    }
}