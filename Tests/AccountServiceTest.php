<?php

namespace JustGivingApi\Tests;

use PHPUnit\Framework\TestCase;
use JustGivingApi\JustGivingApi;
use JustGivingApi\Models\Account;
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
        $api = new JustGivingApi('API_KEY', null, true);

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
            JustGivingApi::SANDBOX_BASE_URL . '/API_KEY/v1/account/john.doe%40example.com',
            (string)$uri
        );

        $this->assertFalse($result, 'Account does not exist.');
    }

    public function testAccountExistsResturnsTrueIfAccountExists()
    {
        $api = new JustGivingApi('API_KEY', null, true);

        $handlerStack = $this->getMockHandlerStack($container, [
            new Response(200, [], '{"accountType":"None"}') // Need actual content from a call.
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
            JustGivingApi::SANDBOX_BASE_URL . '/API_KEY/v1/account/john.doe%40example.com',
            (string)$uri
        );

        $this->assertTrue($result, 'Account does exist.');
    }

    public function testAccountValidation()
    {
        $api = new JustGivingApi('API_KEY', null, true);

        $handlerStack = $this->getMockHandlerStack($container, [
            new Response(200, [], '{"accountType":"None"}') // Need actual content from a call.
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
            JustGivingApi::SANDBOX_BASE_URL . '/API_KEY/v1/account/john.doe%40example.com',
            (string)$uri
        );

        $this->assertTrue($result, 'Account does exist.');
    }

    public function testAccountRegistration()
    {
        $api = new JustGivingApi('API_KEY', null, true);

        $handlerStack = $this->getMockHandlerStack($container, [
            new Response(200, [], '{"accountType":"None"}') // Need actual content from a call.
        ]);

        $api->setHandlerStack($handlerStack);

        $accountsService = $api->getAccountsService();

        $this->assertEquals(
            'JustGivingApi\Services\AccountsService',
            get_class($accountsService)
        );

        $accountArray = [
            "reference" => "myref",
            "title" => "Mr",
            "firstName" => "Bob",
            "lastName" => "Dobalina",
            "Address.line1" => "22 Acacia Avenue",
            "Address.line2" => "Little Village",
            "Address.townOrCity" => "Sometown",
            "Address.countyOrState" => "Acmetown",
            "Address.country" => "United Kingdom",
            "Address.postcodeOrZipcode" => "AB12 CDE",
            "email" => "bob.dobalina@example.com",
            "password" => "PassWord",
            "acceptTermsAndConditions" => true,
            "causeId" => 123
        ];

        $account = new Account($accountArray);

        $result = $accountsService->accountCreate($account);

        $uri = $container[0]['request']->getUri();

        $this->assertEquals(
            JustGivingApi::SANDBOX_BASE_URL . '/API_KEY/v1/account',
            (string)$uri
        );

        $this->assertEquals(
            'PUT',
            $container[0]['request']->getMethod()
        );

        $body = json_decode($container[0]['request']->getBody(), true);

        $this->assertEquals(
            0,
            count(array_diff_assoc($body, $accountArray)),
            'The array sent to the register user endpoint is the same one we defined.'
        );
    }
}
