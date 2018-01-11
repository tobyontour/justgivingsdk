<?php

namespace JustGivingApi\Tests;

use PHPUnit\Framework\TestCase;
use JustGivingApi\JustGivingApi;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;

class OAuth2Test extends TestCase
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

    public function testGetLoginUrl()
    {
        $api = new JustGivingApi('API_KEY', 'OAUTH_SECRET', true);

        $url = $api->getLoginFormUrl(
            array('openid', 'profile', 'fundraise', 'account'),
            'https://www.example.com/auth',
            'abcdef123456789'
        );

        $this->assertEquals(
            JustGivingApi::SANDBOX_AUTH_BASE_URL . '/connect/authorize?client_id=API_KEY&response_type=code&scope=openid+profile+fundraise+account&redirect_uri=https%3A%2F%2Fwww.example.com%2Fauth&nonce=abcdef123456789',
            $url,
            'Login form URL is correct'
        );
    }

    public function testGetLoginUrlWithState()
    {
        $api = new JustGivingApi('API_KEY', 'OAUTH_SECRET', true);

        $url = $api->getLoginFormUrl(
            array('openid', 'profile', 'fundraise', 'account'),
            'https://www.example.com/auth',
            'abcdef123456789',
            'the quick brown fox'
        );
        $this->assertEquals(
            JustGivingApi::SANDBOX_AUTH_BASE_URL . '/connect/authorize?client_id=API_KEY&response_type=code&scope=openid+profile+fundraise+account&redirect_uri=https%3A%2F%2Fwww.example.com%2Fauth&nonce=abcdef123456789&state=the+quick+brown+fox',
            $url,
            'Login form URL is correct'
        );
    }

    public function testGetLoginUrlWithBadUrl()
    {
        $api = new JustGivingApi('API_KEY', 'OAUTH_SECRET', true);

        $this->expectException(\InvalidArgumentException::class);

        $url = $api->getLoginFormUrl(
            array('openid', 'profile', 'fundraise', 'account'),
            '/auth',
            'abcdef123456789',
            'the quick brown fox'
        );
    }

    public function testGetToken()
    {
        $api = new JustGivingApi('API_KEY', 'OAUTH_SECRET', true);

        $data = <<<__EOD__
{"id_token":"eyJ0eXAiOiJKV1QiLclippedPC2R1s035w", "access_token":"a8dfad6cfe559ae0d6e37426b3e0d078", "expires_in":3600,"token_type":"Bearer"}
__EOD__;

        $handlerStack = $this->getMockHandlerStack($container, [
            new Response(200, [], $data)
        ]);

        $api->setHandlerStack($handlerStack);

        $token = $api->getAuthenticationToken('A1B2C3D4', 'https://www.example.com/auth');

        $this->assertEquals(
            'a8dfad6cfe559ae0d6e37426b3e0d078',
            $token,
            'Token is parsed from response correctly.'
        );

        // Now verify we sent the request correctly.
        $request = $container[0]['request'];

        $this->assertEquals(
            JustGivingApi::SANDBOX_AUTH_BASE_URL . '/connect/token',
            (string)$request->getUri()
        );

        parse_str((string)$request->getBody(), $bodyData);

        $this->assertEquals('A1B2C3D4', $bodyData['code']);
        $this->assertEquals('https://www.example.com/auth', $bodyData['redirect_uri']);
        $this->assertEquals('authorization_code', $bodyData['grant_type']);

        $headers = $request->getHeaders();

        $this->assertEquals(
            'Basic Ok9BVVRIX1NFQ1JFVA==',
            $headers['Authorization'][0]
        );
        $this->assertEquals(
            'application/x-www-form-urlencoded',
            $headers['Content-Type'][0]
        );
    }
}
