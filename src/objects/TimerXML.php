<?php

namespace datagutten\dreambox\web\objects;

use SimpleXMLElement;

class TimerXML extends XMLData
{
    /**
     * @var timer
     */
    public $timer;

    public function __construct(SimpleXMLElement $timer_xml)
    {
        parent::__construct($timer_xml);
        self::validate_element($timer_xml, 'e2timer');
        $this->timer = new timer();

        $this->timer->channel_id = $this->string('e2servicereference');
        $this->timer->channel_name = $this->string('e2servicename');
        $this->timer->eit = $this->string('e2eit');
        $this->timer->name = $this->string('e2name');
        $this->timer->description = $this->string('e2description');
        $this->timer->description_extended = $this->string('e2descriptionextended');
        $this->timer->disabled = $this->bool('e2disabled');
        $this->timer->time_begin = $this->int('e2timebegin');
        $this->timer->start = $this->timer->time_begin;
        $this->timer->time_end = $this->int('e2timeend');
        $this->timer->end = $this->timer->time_end;
        $this->timer->duration = $this->int('e2duration');
        $this->timer->start_prepare = $this->int('e2startprepare');
        $this->timer->just_play = $this->bool('e2justplay');
        $this->timer->after_event = $this->int('e2afterevent');
        $this->timer->location = $this->string('e2location');
        $this->timer->tags = $this->string('e2tags');
        $this->timer->log_entries = $this->parse_log($this->string('e2logentries'));
        $this->timer->file_name = $this->string('e2filename');
        $this->timer->back_off = $this->string('e2backoff');
        $this->timer->next_activation = $this->int('e2nextactivation');
        $this->timer->first_try_prepare = $this->bool('e2firsttryprepare');
        $this->timer->state = $this->int('e2state');
        $this->timer->repeated = $this->int('e2repeated');
        $this->timer->dont_save = $this->bool('e2dontsave');
        $this->timer->canceled = $this->bool('e2cancled');
    }

    public function parse_log($log): array
    {
        $entries = [];
        preg_match_all('#\(([0-9]+), ([0-9]+), [\'"](.+?)[\'"]\)(?:, )?#', $log, $matches);
        for ($i = 0; $i < count($matches[0]); $i++)
        {
            $entries[] = [(int)$matches[1][$i], (int)$matches[2][$i], $matches[3][$i]];
        }
        return $entries;
    }
}