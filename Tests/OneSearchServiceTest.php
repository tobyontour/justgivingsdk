<?php

namespace JustGivingApi\Tests;

use JustGivingApi\Tests\ServiceTestBase;
use JustGivingApi\JustGivingApi;
use JustGivingApi\Models\Query;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;

class OneSearchServiceTest extends ServiceTestBase
{
    public function testSimpleQuery()
    {
        $api = $this->initApi(
            $container,
            file_get_contents($this->getMockDataFilename('OneSearch_q_gosh.json'))
        );

        $query = new Query('gosh');

        $results = $api->search($query);

        $this->assertTrue($this->validateMethodAndUrl(
            $container[0]['request'],
            'GET',
            JustGivingApi::SANDBOX_BASE_URL . '/API_KEY/v1/onesearch?q=gosh',
            $body
        ), 'Method and URL are correct.');

        $this->assertTrue(is_object($results));
        $this->assertTrue(is_array($results->GroupedResults));
        $this->assertEquals(760, $results->Total);
        $this->assertEquals('gosh', $results->Query);
    }

    public function testSimpleQueryNoResults()
    {
        $api = $this->initApi(
            $container,
            file_get_contents($this->getMockDataFilename('OneSearch_q_goshzzzz.json'))
        );

        $query = new Query('goshzzzz');

        $results = $api->search($query);

        $this->assertTrue($this->validateMethodAndUrl(
            $container[0]['request'],
            'GET',
            JustGivingApi::SANDBOX_BASE_URL . '/API_KEY/v1/onesearch?q=goshzzzz',
            $body
        ), 'Method and URL are correct.');

        $this->assertTrue(is_object($results));
        $this->assertEquals(0, $results->Total);
        $this->assertEquals('goshzzzz', $results->Query);
    }

    public function testIndexQuery()
    {
        $api = $this->initApi(
            $container,
            file_get_contents($this->getMockDataFilename('OneSearch_q_gosh_i_charity.json'))
        );

        $query = new Query('gosh');
        $query->index = 'charity';

        $results = $api->search($query);

        $this->assertTrue($this->validateMethodAndUrl(
            $container[0]['request'],
            'GET',
            JustGivingApi::SANDBOX_BASE_URL . '/API_KEY/v1/onesearch?q=gosh&i=charity',
            $body
        ), 'Method and URL are correct.');

        $this->assertTrue(is_object($results));
        $this->assertEquals(2, $results->Total);
        $this->assertEquals('gosh', $results->Query);
    }
}
