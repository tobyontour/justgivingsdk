<?php

/**
 * Event class that encapsulates what makes an event in JustGiving.
 */

namespace JustGivingApi\Models;

/**
 * Event class that encapsulates what makes an event in JustGiving.
 */
class Event extends Model
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
}
