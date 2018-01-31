<?php

namespace JustGivingApi\Tests;

use PHPUnit\Framework\TestCase;
use JustGivingApi\JustGivingApi;
use JustGivingApi\Models\FundraisingPage;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;

class FundraisingServiceTest extends TestCase
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

    /**
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

    protected function initApi(&$container, $body = '', $statusCode = 200)
    {
        $api = new JustGivingApi('API_KEY', null, true);

        $handlerStack = $this->getMockHandlerStack($container, [
            new Response($statusCode, [], $body)
        ]);

        $api->setHandlerStack($handlerStack);

        return $api;
    }

    public function testRegisterFundraisingPage()
    {
        $api = new JustGivingApi('API_KEY', null, true);

        $handlerStack = $this->getMockHandlerStack($container, [
            new Response(200, [], '{
              "Next.rel": "string",
              "Next.uri": "string",
              "Next.type": "string",
              "Error.id": "string",
              "Error.desc": "string",
              "pageId": 0,
              "signOnUrl": "string",
              "errorMessage": "string"
            }') // Need actual content from a call.
        ]);

        $api->setHandlerStack($handlerStack);

        $fundraisingService = $api->getFundraisingService();

        $this->assertEquals(
            'JustGivingApi\Services\FundraisingService',
            get_class($fundraisingService)
        );

        // This data may be incorrect.
        // See: https://api.justgiving.com/docs/resources/v1/Fundraising/RegisterFundraisingPage
        $pageArray = [
            "reference" => "OUR_REF",
            "charityId" => 123,
            "eventId" => 234,
            "pageShortName" => "SHORT_NAME",
            "pageTitle" => "PAGE TITLE",
            "activityType" => "Marathon",
            "targetAmount" => "12345",
            "charityOptIn" => true,
            "eventDate" => "2018-01-15T14:50:01.543Z",
            "eventName" => "Event Name",
            "attribution" => "string",
            "charityFunded" => true,
            "causeId" => 345,
            "companyAppealId" => 456,
            "expiryDate" => "2018-01-15T14:50:01.543Z",
            "pageStory" => "string1",
            "pageSummaryWhat" => "string2",
            "pageSummaryWhy" => "string3",
            "theme" => [
                "pageBackground" => "stringA",
                "buttonsThermometerFill" => "stringB",
                "linesThermometerBackground" => "stringC",
                "backgroundColour" => "stringD",
                "titleColour" => "string",
                "buttonColour" => "stringE"
            ],
            "rememberedPersonReference" => [
                "relationship" => "string",
                "rememberedPerson" => [
                    "id" => 0,
                    "firstName" => "string",
                    "lastName" => "string",
                    "town" => "string",
                    "dateOfBirth" => "2018-01-15T14:50:01.543Z",
                    "dateOfDeath" => "2018-01-15T14:50:01.543Z",
                    "errorMessage" => "string"
                ],
            ],
            "consistentErrorResponses" => true,
            "teamId" => 567,
            "currency" => "GBP"
        ];

        $page = new FundraisingPage($pageArray);

        $result = $fundraisingService->createPage($page);

        $this->assertTrue($this->validateMethodAndUrl(
            $container[0]['request'],
            'PUT',
            JustGivingApi::SANDBOX_BASE_URL . '/API_KEY/v1/fundraising/pages',
            $body
        ), 'Method and URL are correct.');

        $this->assertEquals(
            0,
            count(array_diff_assoc($pageArray, $body)),
            'The array sent to the register fundraising page endpoint is the same one we defined.'
        );
    }

    public function testGetPageUpdateById()
    {
        $api = $this->initApi(
            $container,
            '{
            "Id": 123,
            "Video": "string",
            "CreatedDate": "2018-01-24T15:19:24.859Z",
            "Message": "Ipsum lorem"
            }',
            200
        );

        $fundraisingService = $api->getFundraisingService();

        $update = $fundraisingService->getPageUpdateById('test-page', 123);

        $this->assertTrue($this->validateMethodAndUrl(
            $container[0]['request'],
            'GET',
            JustGivingApi::SANDBOX_BASE_URL . '/API_KEY/v1/fundraising/pages/test-page/updates/' . 123,
            $body
        ), 'Method and URL are correct.');

        $this->assertEquals(
            'Ipsum lorem',
            $update->Message
        );
    }

    public function testGetPageUpdates()
    {
        $api = $this->initApi(
            $container,
            '[]', // Need actual data.
            200
        );

        $fundraisingService = $api->getFundraisingService();

        $updates = $fundraisingService->getPageUpdates('test-page');

        $this->assertTrue($this->validateMethodAndUrl(
            $container[0]['request'],
            'GET',
            JustGivingApi::SANDBOX_BASE_URL . '/API_KEY/v1/fundraising/pages/test-page/updates',
            $body
        ), 'Method and URL are correct.');

        $this->assertTrue(
            is_array($updates)
        );
    }

    public function testSuggestPageShortNames()
    {
        $api = $this->initApi(
            $container,
            file_get_contents(__DIR__ . '/Mockdata/SuggestPageShortNames.json'),
            200
        );

        $fundraisingService = $api->getFundraisingService();

        $suggestions = $fundraisingService->getShortNameSuggestions('jon');

        $this->assertTrue($this->validateMethodAndUrl(
            $container[0]['request'],
            'GET',
            JustGivingApi::SANDBOX_BASE_URL . '/API_KEY/v1/fundraising/pages/suggest?preferredName=jon',
            $body
        ), 'Method and URL are correct.');

        $this->assertTrue(
            is_array($suggestions)
        );
    }

    public function testFundraisingPageUrlCheckUrlAvailable()
    {
        $api = $this->initApi(
            $container,
            '',
            404
        );

        $isUrlInUse = $api->getFundraisingService()->isUrlInUse('jon');

        $this->assertTrue($this->validateMethodAndUrl(
            $container[0]['request'],
            'HEAD',
            JustGivingApi::SANDBOX_BASE_URL . '/API_KEY/v1/fundraising/pages/jon',
            $body
        ), 'Method and URL are correct.');

        $this->assertFalse(
            $isUrlInUse,
            'Returns false if the Url is not in use (HEAD returns 404)'
        );
    }

    public function testFundraisingPageUrlCheckUrlNotAvailable()
    {
        $api = $this->initApi(
            $container,
            '',
            200
        );

        $isUrlInUse = $api->getFundraisingService()->isUrlInUse('jon');

        $this->assertTrue($this->validateMethodAndUrl(
            $container[0]['request'],
            'HEAD',
            JustGivingApi::SANDBOX_BASE_URL . '/API_KEY/v1/fundraising/pages/jon',
            $body
        ), 'Method and URL are correct.');

        $this->assertTrue(
            $isUrlInUse,
            'Returns true if the Url is in use (HEAD returns 200)'
        );
    }

    public function testFundraisingPageUrlCheckUrlInvalidUrl()
    {
        $api = $this->initApi(
            $container,
            '',
            400
        );

        $this->expectException(\InvalidArgumentException::class);

        $isUrlInUse = $api->getFundraisingService()->isUrlInUse('jon');

        $this->assertTrue($this->validateMethodAndUrl(
            $container[0]['request'],
            'HEAD',
            JustGivingApi::SANDBOX_BASE_URL . '/API_KEY/v1/fundraising/pages/jon',
            $body
        ), 'Method and URL are correct.');

        $this->assertFalse(
            $isUrlInUse,
            'Returns false if the Url is not in use but invalid (HEAD returns 400)'
        );
    }

    public function testGetFundraisingPageDetails()
    {
        $api = $this->initApi(
            $container,
            file_get_contents(__DIR__ . '/Mockdata/GetFundraisingPageDetails.json'),
            200
        );

        $page = $api->getFundraisingService()->getPageDetails('test-page');

        $this->assertTrue($this->validateMethodAndUrl(
            $container[0]['request'],
            'GET',
            JustGivingApi::SANDBOX_BASE_URL . '/API_KEY/v1/fundraising/pages/test-page',
            $body
        ), 'Method and URL are correct.');

        $this->assertEquals(
            'JustGivingApi\Models\FundraisingPage',
            get_class($page)
        );

        $this->assertEquals(
            'My page',
            $page->title
        );
    }

    public function testGetFundraisingPageDetailsById()
    {
        $api = $this->initApi(
            $container,
            file_get_contents(__DIR__ . '/Mockdata/GetFundraisingPageDetails.json'),
            200
        );

        $page = $api->getFundraisingService()->getPageDetailsById(2811920);

        $this->assertTrue($this->validateMethodAndUrl(
            $container[0]['request'],
            'GET',
            JustGivingApi::SANDBOX_BASE_URL . '/API_KEY/v1/fundraising/pagebyid/2811920',
            $body
        ), 'Method and URL are correct.');

        $this->assertEquals(
            'JustGivingApi\Models\FundraisingPage',
            get_class($page)
        );

        $this->assertEquals(
            'My page',
            $page->title
        );
    }
}
