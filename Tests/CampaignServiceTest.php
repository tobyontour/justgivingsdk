<?php

namespace JustGivingApi\Tests;

use PHPUnit\Framework\TestCase;
use JustGivingApi\JustGivingApi;
use JustGivingApi\Models\Account;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;

class CampaignServiceTest extends TestCase
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

    public function testCampaignsServiceExists()
    {
        $api = new JustGivingApi('API_KEY', null, false);

        $campaignsService = $api->getCampaignsService();

        $this->assertEquals(
            'JustGivingApi\Services\CampaignsService',
            get_class($campaignsService)
        );
    }

    public function testGetCampaignsByCharityId()
    {
        $api = new JustGivingApi('API_KEY', null, false);

        $handlerStack = $this->getMockHandlerStack($container, [
            new Response(200, [], file_get_contents(__DIR__ . '/Mockdata/GetCampaignsByCharityId.json'))
        ]);

        $api->setHandlerStack($handlerStack);

        $campaignsService = $api->getCampaignsService();

        $results = $campaignsService->getCampaignsByCharityId(254);

        $this->assertEquals(
            2,
            count($results),
            'There are two campaigns.'
        );

        $this->assertEquals(
            'JustGivingApi\Models\Campaign',
            get_class($results[0]),
            'First object is a Campaign'
        );

        $this->assertEquals(
            'JustGivingApi\Models\Campaign',
            get_class($results[1]),
            'Second object is a Campaign'
        );

        // Spot test some data.
        $this->assertEquals('Gordon and Tana Ramsay Foundation', $results[1]->campaignPageName);
        $this->assertEquals(true, $results[1]->fundraisingEnabled);
        $this->assertEquals(2, count($results[1]->images));
    }
}
