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
            "CustomCodes.customCode1" => "string4",
            "CustomCodes.customCode2" => "string5",
            "CustomCodes.customCode3" => "string6",
            "CustomCodes.customCode4" => "string7",
            "CustomCodes.customCode5" => "string8",
            "CustomCodes.customCode6" => "string9",
            "Theme.pageBackground" => "stringA",
            "Theme.buttonsThermometerFill" => "stringB",
            "Theme.linesThermometerBackground" => "stringC",
            "Theme.backgroundColour" => "stringD",
            "Theme.buttonColour" => "stringE",
            /*
            "Theme.titleColour" => "string",
            "RememberedPersonReference.relationship" => "string",
            "RememberedPersonReference.RememberedPerson.id" => 0,
            "RememberedPersonReference.RememberedPerson.firstName" => "string",
            "RememberedPersonReference.RememberedPerson.lastName" => "string",
            "RememberedPersonReference.RememberedPerson.town" => "string",
            "RememberedPersonReference.RememberedPerson.dateOfBirth" => "2018-01-15T14:50:01.543Z",
            "RememberedPersonReference.RememberedPerson.dateOfDeath" => "2018-01-15T14:50:01.543Z",
            "RememberedPersonReference.RememberedPerson.errorMessage" => "string",
            "RememberedPersonReference.errorMessage" => "string",
            */
            "consistentErrorResponses" => true,
            "teamId" => 567,
            "currency" => "GBP"
        ];

        $page = new FundraisingPage($pageArray);

        $result = $fundraisingService->pageCreate($page);

        $uri = $container[0]['request']->getUri();

        $this->assertEquals(
            JustGivingApi::SANDBOX_BASE_URL . '/API_KEY/v1/fundraising/pages',
            (string)$uri
        );

        $this->assertEquals(
            'PUT',
            $container[0]['request']->getMethod()
        );

        $body = json_decode($container[0]['request']->getBody(), true);

        $this->assertEquals(
            0,
            count(array_diff_assoc($body, $pageArray)),
            'The array sent to the register fundraising page endpoint is the same one we defined.'
        );
    }
}
