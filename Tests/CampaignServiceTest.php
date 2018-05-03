<?php

namespace JustGivingApi\Tests;

use JustGivingApi\Models\Campaign;
use JustGivingApi\Services\CampaignsService;

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

    public function testCreateCampaign()
    {
        $api = new JustGivingApi('API_KEY', null, false);

        $handlerStack = $this->getMockHandlerStack($container, [
            new Response(200, [], '{
  "charityShortName": "short name",
  "campaignShortUrl": "short url",
  "campaignFullUrl": "full url",
  "errorMessage": "string"
}') // Need actual content from a call.
        ]);

        $api->setHandlerStack($handlerStack);

        $campaignArray = array(
            'campaignDeadline' => '2019-05-03T10:06:58.000Z',
            'campaignThankYouMessage' => 'Thank you for the music',
            'campaignPageName' => 'Race For The Santa 2018',
            'campaignUrl' => 'http://campaign.staging.justgiving.com/charity/greatormondstreet/rfts',
            'currency' => 'GBP',
            'target' => '1000000',
            'fundraisingEnabled' => true,
            'story' => 'This is the story all about how my life got flipped turned upside down.',
        );
        $campaign = new Campaign($campaignArray);

        $result = $api->getCampaignsService()->createCampaign($campaign);

        $uri = $container[0]['request']->getUri();

        $this->assertEquals(
            JustGivingApi::SANDBOX_BASE_URL . '/API_KEY/v1/campaigns',
            (string)$uri
        );

        $this->assertEquals(
            'PUT',
            $container[0]['request']->getMethod()
        );

        $body = json_decode($container[0]['request']->getBody(), true);

        $expectedBody = array (
          'campaignDeadline' => '2019-05-03T10:06:58.000Z',
          'campaignThankYouMessage' => 'Thank you for the music',
          'campaignName' => 'Race For The Santa 2018',
          'campaignUrl' => 'http://campaign.staging.justgiving.com/charity/greatormondstreet/rfts',
          'currencyCode' => 'GBP',
          'campaignTarget' => '1000000',
          'campaignStory' => 'This is the story all about how my life got flipped turned upside down.',
          'fundraisingEnabled' => true,
          'campaignSummary' => 'Summary'
        );

        $this->assertEquals(
            0,
            count(array_diff_assoc($body, $expectedBody)),
            'The array sent to the register user endpoint is the same one we defined. ' .
              print_r(array_diff_assoc($expectedBody, $body), true)
        );

        $this->assertTrue(isset($result->charityShortName), 'Return contains charityShortName');
        $this->assertTrue(isset($result->campaignShortUrl), 'Return contains campaignShortUrl');
        $this->assertTrue(isset($result->campaignFullUrl), 'Return contains campaignFullUrl');
        $this->assertTrue(isset($result->errorMessage), 'Return contains errorMessage');
    }
}
