<?php

namespace JustGivingApi\Services;

class EventsService extends Service
{
    public function getTypes()
    {
        $data = $this->get('event/types');
        return $data->eventTypes;
    }
}
