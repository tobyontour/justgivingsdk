<?php

namespace JustGivingApi\Tests;

use JustGivingApi\Models\Event;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    public function testCreateEventFromArray()
    {
        $event = new Event(
            array(
                'name' => 'Test event name',
                'description' => 'Event description',
                'id' => 123,
                'completionDate' => '2018-01-09T10:36:12.338Z',
                'expiryDate' => '2018-01-09T10:36:12.338Z',
                'startDate' => '2018-01-09T10:36:12.338Z',
                'eventType' => 'running',
                'location' => 'London'
            )
        );

        $this->assertTrue(
            is_a($event, 'JustGivingApi\Models\Event'),
            'Event type created correctly'
        );

        $this->assertEquals('Test event name', $event->name);
        $this->assertEquals('Event description', $event->description);
        $this->assertEquals(123, $event->id);
        $this->assertEquals('2018-01-09T10:36:12.338Z', $event->completionDate);
        $this->assertEquals('2018-01-09T10:36:12.338Z', $event->expiryDate);
        $this->assertEquals('2018-01-09T10:36:12.338Z', $event->startDate);
        $this->assertEquals('running', $event->eventType);
        $this->assertEquals('London', $event->location);
    }

    public function testCreateEvent()
    {
        $event = new Event();

        $this->assertTrue(
            is_a($event, 'JustGivingApi\Models\Event'),
            'Event type created correctly'
        );

        $this->assertEquals('', $event->name);
        $this->assertEquals('', $event->description);
        $this->assertNull($event->id);
        $this->assertNull($event->completionDate);
        $this->assertNull($event->expiryDate);
        $this->assertNull($event->startDate);
        $this->assertNull($event->eventType);
        $this->assertNull($event->location);
    }

    public function testToArray()
    {
        $event = new Event();

        $this->assertTrue(
            is_a($event, 'JustGivingApi\Models\Event'),
            'Event type created correctly'
        );

        $arr = $event->toArray();

        $this->assertEquals('', $arr['name']);
        $this->assertEquals('', $arr['description']);
        $this->assertEquals(2, count($arr), 'Null elements removed from array.');
    }
}
