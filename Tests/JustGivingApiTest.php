<?php

namespace JustGivingApi\Tests;

use PHPUnit\Framework\TestCase;
use JustGivingApi\JustGivingApi;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;

class JustGivingApiTest extends TestCase
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
        $api = new JustGivingApi('http://example.com/abc/def', 'API_KEY');

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
            'http://example.com/abc/def/API_KEY/event/types',
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
        $api = new JustGivingApi('http://example.com/abc/def', 'API_KEY');

        $handlerStack = $this->getMockHandlerStack($container, [
            new Response(403, [], '[{"id":"appIdNotFound","desc":"Supplied Application ID not found access is forbidden."}]')
        ]);

        $api->setHandlerStack($handlerStack);

        $events = $api->getEventsService();

        $this->expectException(\RuntimeException::class);

        $eventTypes = $events->getTypes();
    }
}