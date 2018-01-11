<?php

namespace JustGivingApi\Tests;

use PHPUnit\Framework\TestCase;
use JustGivingApi\JustGivingApi;
use JustGivingApi\Models\Event;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;

class EventsServiceTest extends TestCase
{
    protected function getMockHandlerStack(&$container, array $responses = [])
    {
        $container = [];

        $history = Middleware::history($container);
        $mock = new MockHandler($responses);
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);
        return $handlerStack;
    }

    public function testGetEventTypes()
    {
        $api = new JustGivingApi('API_KEY', null, true);

        $handlerStack = $this->getMockHandlerStack($container, [
            new Response(200, [], '{"eventTypes":[{"description":"From the Flora London Marathon to a \'fun run\' round your local park, running for charity is a great way to get in shape and raise money for your favourite causes. \u000a\u000a","eventType":"Running_Marathons","id":1,"name":"Running \/ marathons"}]}')
        ]);

        $api->setHandlerStack($handlerStack);

        $events = $api->getEventsService();

        $eventTypes = $events->getTypes();

        $this->assertTrue(
            is_array($eventTypes),
            'GetEventTypes REST call returns an array'
        );

        $uri = $container[0]['request']->getUri();

        $this->assertEquals(
            JustGivingApi::SANDBOX_BASE_URL . '/API_KEY/v1/event/types',
            (string)$uri
        );

        $headers = $container[0]['request']->getHeaders();

        $this->assertEquals(
            'application/json',
            $headers['Accept'][0]
        );
    }

    public function testGetEventTypesForbiddenError()
    {
        $api = new JustGivingApi('API_KEY', null, true);

        $handlerStack = $this->getMockHandlerStack($container, [
            new Response(403, [], '[{"id":"appIdNotFound","desc":"Supplied Application ID not found access is forbidden."}]')
        ]);

        $api->setHandlerStack($handlerStack);

        $events = $api->getEventsService();

        $this->expectException(\RuntimeException::class);

        $eventTypes = $events->getTypes();
    }

    public function testGetEventById()
    {
        $api = new JustGivingApi('API_KEY', null, true);

        $handlerStack = $this->getMockHandlerStack($container, [
            new Response(200, [], '{"name": "string", "description": "string", "id": 0, "completionDate": "2017-12-18T15:20:08.036Z", "expiryDate": "2017-12-18T15:20:08.036Z", "startDate": "2017-12-18T15:20:08.036Z", "eventType": "string", "location": "string", "errorMessage": "string"}')
        ]);

        $api->setHandlerStack($handlerStack);

        $events = $api->getEventsService();

        $event = $events->getEventById(0);

        $this->assertTrue(
            is_a($event, 'JustGivingApi\Models\Event'),
            'GetEventById REST call returns an Event'
        );

        $uri = $container[0]['request']->getUri();

        $this->assertEquals(
            JustGivingApi::SANDBOX_BASE_URL . '/API_KEY/v1/event/0',
            (string)$uri
        );

        $headers = $container[0]['request']->getHeaders();

        $this->assertEquals(
            'application/json',
            $headers['Accept'][0]
        );
    }

    public function testGetEventByIdInvalidId()
    {
        $api = new JustGivingApi('API_KEY', null, true);

        $handlerStack = $this->getMockHandlerStack($container, [
            new Response(200, [], '{"name": "string", "description": "string", "id": 0, "completionDate": "2017-12-18T15:20:08.036Z", "expiryDate": "2017-12-18T15:20:08.036Z", "startDate": "2017-12-18T15:20:08.036Z", "eventType": "string", "location": "string", "errorMessage": "string"}')
        ]);

        $api->setHandlerStack($handlerStack);

        $events = $api->getEventsService();

        $this->expectException(\InvalidArgumentException::class);
        $event = $events->getEventById('qwerty');
    }

    public function testGetPagesForEvent()
    {
        $api = new JustGivingApi('API_KEY', null, true);

        $handlerStack = $this->getMockHandlerStack($container, [
            new Response(200, [], file_get_contents(__DIR__ . '/Mockdata/GetPagesForEvent.json'))
        ]);

        $api->setHandlerStack($handlerStack);

        $events = $api->getEventsService();

        $pages = $events->getPagesForEvent(246);

        $this->assertTrue(
            is_object($pages),
            'GetEventTypes REST call returns an object'
        );

        $uri = $container[0]['request']->getUri();

        $this->assertEquals(
            JustGivingApi::SANDBOX_BASE_URL . '/API_KEY/v1/event/246/pages',
            (string)$uri
        );

        $headers = $container[0]['request']->getHeaders();

        $this->assertEquals(
            'application/json',
            $headers['Accept'][0]
        );
    }

    public function testGetPagesForEventInvalidId()
    {
        $api = new JustGivingApi('API_KEY', null, true);

        $handlerStack = $this->getMockHandlerStack($container, [
            new Response(200, [], '')
        ]);

        $api->setHandlerStack($handlerStack);

        $events = $api->getEventsService();

        $this->expectException(\InvalidArgumentException::class);
        $event = $events->getPagesForEvent('qwerty');
    }

    public function testGetPagesForEventInvalidPageNumber()
    {
        $api = new JustGivingApi('API_KEY', null, true);

        $handlerStack = $this->getMockHandlerStack($container, [
            new Response(200, [], '')
        ]);

        $api->setHandlerStack($handlerStack);

        $events = $api->getEventsService();

        $this->expectException(\InvalidArgumentException::class);
        $event = $events->getPagesForEvent(1, 'qwerty');
    }

    public function testGetPagesForEventInvalidNegativePageNumber()
    {
        $api = new JustGivingApi('API_KEY', null, true);

        $handlerStack = $this->getMockHandlerStack($container, [
            new Response(200, [], '')
        ]);

        $api->setHandlerStack($handlerStack);

        $events = $api->getEventsService();

        $this->expectException(\InvalidArgumentException::class);
        $event = $events->getPagesForEvent(1, -1);
    }


    public function testGetPagesForEventInvalidPageSize()
    {
        $api = new JustGivingApi('API_KEY', null, true);

        $handlerStack = $this->getMockHandlerStack($container, [
            new Response(200, [], '')
        ]);

        $api->setHandlerStack($handlerStack);

        $events = $api->getEventsService();

        $this->expectException(\InvalidArgumentException::class);
        $event = $events->getPagesForEvent(1, 1, 'qwerty');
    }

    public function testGetPagesForEventInvalidNegativePageSize()
    {
        $api = new JustGivingApi('API_KEY', null, true);

        $handlerStack = $this->getMockHandlerStack($container, [
            new Response(200, [], '')
        ]);

        $api->setHandlerStack($handlerStack);

        $events = $api->getEventsService();

        $this->expectException(\InvalidArgumentException::class);
        $event = $events->getPagesForEvent(1, 1, -1);
    }

    public function testGetPagesForEventPageSizeOverLimit()
    {
        $api = new JustGivingApi('API_KEY', null, true);

        $handlerStack = $this->getMockHandlerStack($container, [
            new Response(200, [], '')
        ]);

        $api->setHandlerStack($handlerStack);

        $events = $api->getEventsService();

        $event = $events->getPagesForEvent(1, 1, 101);

        $uri = $container[0]['request']->getUri();

        $this->assertEquals(
            JustGivingApi::SANDBOX_BASE_URL . '/API_KEY/v1/event/1/pages?page=1&pagesize=100',
            (string)$uri
        );
    }

    public function testGetPagesForEventPageSizeOnly()
    {
        $api = new JustGivingApi('API_KEY', null, true);

        $handlerStack = $this->getMockHandlerStack($container, [new Response(200, [], '')]);
        $api->setHandlerStack($handlerStack);

        $event = $api->getEventsService()->getPagesForEvent(123, null, 3);

        $uri = $container[0]['request']->getUri();

        $this->assertEquals(
            JustGivingApi::SANDBOX_BASE_URL . '/API_KEY/v1/event/123/pages?pagesize=3',
            (string)$uri
        );
    }

    public function testGetPagesForEventPageNumberOnly()
    {
        $api = new JustGivingApi('API_KEY', null, true);

        $handlerStack = $this->getMockHandlerStack($container, [new Response(200, [], '')]);
        $api->setHandlerStack($handlerStack);

        $event = $api->getEventsService()->getPagesForEvent(123, 5);

        $uri = $container[0]['request']->getUri();

        $this->assertEquals(
            JustGivingApi::SANDBOX_BASE_URL . '/API_KEY/v1/event/123/pages?page=5',
            (string)$uri
        );
    }

    public function testRegisterEvent()
    {
        $api = new JustGivingApi('API_KEY', null, true);

        $handlerStack = $this->getMockHandlerStack($container, [new Response(201, [], file_get_contents(__DIR__ . '/mockdata/RegisterEvent.json'))]);
        $api->setHandlerStack($handlerStack);

        $newEvent = new Event(
            array(
                'name' => 'Test event name',
                'description' => 'Event description',
                'id' => 123
            )
        );

        $event = $api->getEventsService()->createEvent($newEvent);

        $this->assertTrue(is_object($event));
        $this->assertTrue(isset($event->id) && is_numeric($event->id), 'Event ID is set and valid.');
        $this->assertTrue(
            isset($event->next->uri) && filter_var($event->next->uri, FILTER_VALIDATE_URL),
            'Returned URI is valid'
        );
    }
}
