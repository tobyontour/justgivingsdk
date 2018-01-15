<?php

/**
 * Event class that encapsulates what makes an event in JustGiving.
 */

namespace JustGivingApi\Models;

/**
 * Event class that encapsulates what makes an event in JustGiving.
 */
class Event
{
    /**
     * The name of the event.
     * @var string
     */
    public $name = "";

    /**
     * Teh description of the event.
     * @var string
     */
    public $description = "";

    /**
     * The ID of the event
     * @var int
     */
    public $id;

    /**
     * The date the event will be complete. In the JustGiving date format.
     *
     * See \JustGivingApi\JUSTGIVINGAPI_DATE_FORMAT
     *
     * @var string
     */
    public $completionDate;

    /**
     * The date the event will expire. In the JustGiving date format.
     *
     * See \JustGivingApi\JUSTGIVINGAPI_DATE_FORMAT
     *
     * @var string
     */
    public $expiryDate;

    /**
     * The date the event will start. In the JustGiving date format.
     *
     * See \JustGivingApi\JUSTGIVINGAPI_DATE_FORMAT
     *
     * @var string
     */
    public $startDate;

    /**
     * The type of event. A limited set of strings.
     * @var string
     */
    public $eventType;

    /**
     * The location of the event.
     * @var string
     */
    public $location;

    /**
     * The ID of the charity the event is for.
     * @var [type]
     */
    public $charityId;

    /**
     * Error message if the call failed.
     * @var string
     */
    public $errorMessage;

    /**
     * Constructor.
     *
     * @param array An array to prepopulate the event with, often from a JSON decode response.
     */
    public function __construct(array $data = [])
    {
        $this->name = isset($data['name']) ? $data['name'] : '';
        $this->description = isset($data['description']) ? $data['description'] : '';
        $this->id = isset($data['id']) ? $data['id'] : null;
        $this->completionDate = isset($data['completionDate']) ? $data['completionDate'] : null;
        $this->expiryDate = isset($data['expiryDate']) ? $data['expiryDate'] : null;
        $this->startDate = isset($data['startDate']) ? $data['startDate'] : null;
        $this->eventType = isset($data['eventType']) ? $data['eventType'] : null;
        $this->location = isset($data['location']) ? $data['location'] : null;
        $this->charityId = isset($data['charityId']) ? $data['charityId'] : null;
    }

    /**
     * Convert the object to an array.
     *
     * @return array The array to send as part of a REST request.
     */
    public function toArray()
    {
        $arr = array(
            'name' => $this->name,
            'description' => $this->description,
            'id' => $this->id,
            'completionDate' => $this->completionDate,
            'expiryDate' => $this->expiryDate,
            'startDate' => $this->startDate,
            'eventType' => $this->eventType,
            'location' => $this->location
        );

        foreach ($arr as $key => $value) {
            if (is_null($value)) {
                unset($arr[$key]);
            }
        }

        return $arr;
    }
}
