<?php

namespace JustGivingApi\Services;

use JustGivingApi\Models\Event;

class EventsService extends Service
{
    public function getTypes()
    {
        $data = $this->get('event/types');
        return $data->eventTypes;
    }

    public function getEventById($id)
    {
        if (!is_numeric($id)) {
            throw new \InvalidArgumentException('ID should be numeric.');
        }
        $data = $this->get('event/' . intval($id), true);

        return new Event($data);
    }

    public function getPagesForEvent($id, $pageNumber = null, $pageSize = null)
    {
        if (!is_numeric($id)) {
            throw new \InvalidArgumentException('ID should be numeric.');
        }

        $path = 'event/' . intval($id) . '/pages';
        $params = [];

        if (!is_null($pageNumber)) {
            if (!is_numeric($pageNumber) || $pageNumber < 0) {
                throw new \InvalidArgumentException('Page number should be a positive integer or 0.');
            }
            $params['page'] = $pageNumber;
        }

        if (!is_null($pageSize)) {
            if (!is_numeric($pageSize) || $pageSize <= 0) {
                throw new \InvalidArgumentException('Page size should be a positive integer greater than 0.');
            }

            if ($pageSize > 100) {
                $pageSize = 100;
            }

            $params['pagesize'] = $pageSize;
        }

        if (count($params) != 0) {
            $path .= '?' . http_build_query($params);
        }

        $data = $this->get($path);
        return $data;
    }

    /**
     * @param  Event The event to create.
     * @return object Contains
     */
    public function createEvent(Event $event)
    {
        $path = 'event';

        $data = $this->post($path, $event->toArray());

        return $data;
    }
}
