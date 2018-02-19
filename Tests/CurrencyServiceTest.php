<?php

namespace JustGivingApi\Tests;

use PHPUnit\Framework\TestCase;
use JustGivingApi\JustGivingApi;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;

class CurrencyServiceTest extends TestCase
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

    public function testGetCurrencies()
    {
        $api = new JustGivingApi('API_KEY', null, false);

        $handlerStack = $this->getMockHandlerStack($container, [
            new Response(200, [], file_get_contents(__DIR__ . '/Mockdata/GetValidCurrencyCodes.json'))
        ]);

        $api->setHandlerStack($handlerStack);

        $currencies = $api->getCurrencyService()->getCurrencies();

        $this->assertTrue(
            is_array($currencies),
            'GetValidCurrencyCodes REST call returns an array'
        );

        $uri = $container[0]['request']->getUri();

        $this->assertEquals(
            JustGivingApi::SANDBOX_BASE_URL . '/API_KEY/v1/fundraising/currencies',
            (string)$uri
        );

        $headers = $container[0]['request']->getHeaders();

        $this->assertEquals(
            'application/json',
            $headers['Accept'][0]
        );

        $this->assertEquals(
            'GBP',
            $currencies[0]->currencyCode
        );

        $this->assertEquals(
            '£',
            $currencies[0]->currencySymbol
        );

        $this->assertEquals(
            'British Pounds',
            $currencies[0]->description
        );
    }
}
