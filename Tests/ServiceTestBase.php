<?php

namespace JustGivingApi\Tests;

use PHPUnit\Framework\TestCase;
use JustGivingApi\JustGivingApi;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;

class ServiceTestBase extends TestCase
{
    /**
     * Gets the mock handler stack.
     *
     * @param  reference &$container Container. Will allow you to get the history of calls
     * @param  array The responses.
     * @return HandlerStack The mocked Handler stack.
     */
    protected function getMockHandlerStack(&$container, array $responses = [])
    {
        $container = [];

        $history = Middleware::history($container);
        $mock = new MockHandler($responses);
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);
        return $handlerStack;
    }

    /**
     * Validates that the method and URL match the expected and returns the body.
     *
     * @param  Request $request The Guzzle request
     * @param  string $method The expected method
     * @param  string $url The expected url
     * @param  string &$body Populated with the body
     * @param  bool $assoc Return the body parsed as an associative array.
     * @return bool True if the request had the correct method and url
     */
    protected function validateMethodAndUrl($request, $method, $url, &$body, $assoc = true)
    {
        if ($request->getUri() != $url) {
            return false;
        }

        if ($request->getMethod() != $method) {
            return false;
        }

        $body = json_decode($request->getBody(), $assoc);

        return true;
    }

    /**
     * Initialise the API and the response to the first Guzzle call.
     *
     * For multiple calls you'll have to handle this all yourself for now.
     *
     * @param  reference &$container The container. Will allow retrival of the history.
     * @param  string $body The body of the first call to return.
     * @param  integer $statusCode The retrun code of the first call.
     * @return JustGivingApi The api.
     */
    protected function initApi(&$container, $body = '', $statusCode = 200)
    {
        $api = new JustGivingApi('API_KEY', null, true);

        $handlerStack = $this->getMockHandlerStack($container, [
            new Response($statusCode, [], $body)
        ]);

        $api->setHandlerStack($handlerStack);

        return $api;
    }

    protected function getMockDataFilename($tail)
    {
        return __DIR__ . '/Mockdata/' . $tail;
    }
}
