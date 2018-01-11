<?php

namespace JustGivingApi\Models;

class Event
{
    public $name = "";
    public $description = "";
    public $id;
    public $completionDate;
    public $expiryDate;
    public $startDate;
    public $eventType;
    public $location;
    public $charityId;
    public $errorMessage;

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
