<?php

namespace JustGivingApi\Tests;

use PHPUnit\Framework\TestCase;
use JustGivingApi\JustGivingApi;
//use JustGivingApi\Models\Account;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;

class AccountServiceTest extends TestCase
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

    public function testAccountExistsReturnsFalseIfAccountDoesNotExist()
    {
        $api = new JustGivingApi('API_KEY', 'http://example.com/abc/def');

        $handlerStack = $this->getMockHandlerStack($container, [
            new Response(404, [], '{"accountType":"None"}')
        ]);

        $api->setHandlerStack($handlerStack);

        $accountsService = $api->getAccountsService();

        $this->assertEquals(
            'JustGivingApi\Services\AccountsService',
            get_class($accountsService)
        );

        $result = $accountsService->accountExists('john.doe@example.com');

        $uri = $container[0]['request']->getUri();

        $this->assertEquals(
            'http://example.com/abc/def/API_KEY/v1/account/john.doe%40example.com',
            (string)$uri
        );

        $this->assertFalse($result, 'Account does not exist.');
    }
}