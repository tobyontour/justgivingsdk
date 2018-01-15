<?php

/**
 * Deals wioth calls to the /event endpoint and child endpoints.
 */

namespace JustGivingApi\Services;

use JustGivingApi\Models\Event;

/**
 * Deals wioth calls to the /event endpoint and child endpoints.
 */
class EventsService extends Service
{
    /**
     * Get the types of event that Just Giving recognise.
     *
     * @return array List of types of events.
     */
    public function getTypes()
    {
        $data = $this->transport->get('event/types');
        return $data->eventTypes;
    }

    /**
     * Get an Event by its ID.
     *
     * @param  string|int $id The numeric ID of the event to retrieve.
     * @return Event The Event object
     */
    public function getEventById($id)
    {
        if (!is_numeric($id)) {
            throw new \InvalidArgumentException('ID should be numeric.');
        }
        $data = $this->transport->get('event/' . intval($id), true);

        return new Event($data);
    }

    /**
     * Get the donation pages associated with an Event.
     *
     * @param  string|int $id The numeric ID of the event to get pages for.
     * @param  int $pageNumber The page number to retrieve.
     * @param  int $pageSize The size of each retrieved page.
     * @return The array of event pages.
     */
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

        $data = $this->transport->get($path);
        return $data;
    }

    /**
     * Create an event.
     *
     * @param  Event The event to create.
     * @return object Contains
     */
    public function createEvent(Event $event)
    {
        $path = 'event';

        $data = $this->transport->post($path, $event->toArray());

        return $data;
    }
}
