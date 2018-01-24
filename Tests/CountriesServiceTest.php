<?php

namespace JustGivingApi\Tests;

use PHPUnit\Framework\TestCase;
use JustGivingApi\JustGivingApi;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;

class CountriesServiceTest extends TestCase
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

    public function testListCountries()
    {
        $api = new JustGivingApi('API_KEY', null, true);

        $handlerStack = $this->getMockHandlerStack($container, [
            new Response(200, [], file_get_contents(__DIR__ . '/Mockdata/ListCountries.json'))
        ]);

        $api->setHandlerStack($handlerStack);

        $countriesService = $api->getCountriesService();

        $countries = $countriesService->listCountries();

        $this->assertTrue(
            is_array($countries),
            'ListCountries REST call returns an array'
        );

        $uri = $container[0]['request']->getUri();

        $this->assertEquals(
            JustGivingApi::SANDBOX_BASE_URL . '/API_KEY/v1/countries',
            (string)$uri
        );

        $headers = $container[0]['request']->getHeaders();

        $this->assertEquals(
            'application/json',
            $headers['Accept'][0]
        );

        $this->assertEquals(
            'AR',
            $countries[10]->countryCode
        );

        $this->assertEquals(
            'Argentina',
            $countries[10]->name
        );
    }
}
