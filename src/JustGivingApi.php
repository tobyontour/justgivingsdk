<?php

namespace JustGivingApi;

use JustGivingApi\Services\EventsService;
use JustGivingApi\Services\AccountsService;
use JustGivingApi\Services\OAuth2Service;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\CurlHandler;

const JUSTGIVINGAPI_DATE_FORMAT = 'Y-m-d\TH:i:s.000\Z';

class JustGivingApi
{
    private $apiKey;
    private $baseUrl;
    private $authBaseUrl;
    private $version;
    private $handler;
    private $secret;

    const SANDBOX_BASE_URL = 'https://api.sandbox.justgiving.com';
    const PRODUCTION_BASE_URL = 'https://api.justgiving.com';
    const SANDBOX_AUTH_BASE_URL = 'https://identity.sandbox.justgiving.com';
    const PRODUCTION_AUTH_BASE_URL = 'https://identity.justgiving.com';

    /**
     * Creates an instance of the API.
     *
     * @param string $apiKey The JustGiving API key. Register as a developer on the website to get this.
     * @param bool $testMode If true it uses the sandbox environment. Defaults to false (production).
     * @param integer $version API version.
     */
    public function __construct($apiKey, $secret = null, $testMode = false, $version = 1)
    {
        if ($testMode) {
            $this->testMode();
        } else {
            $this->liveMode();
        }

        $this->apiKey = $apiKey;
        $this->version = $version;
        $this->handler = new CurlHandler();
        $this->stack = HandlerStack::create($this->handler);
        $this->secret = $secret;
    }

    public function testMode()
    {
        $this->baseUrl = JustGivingApi::SANDBOX_BASE_URL;
        $this->authBaseUrl = JustGivingApi::SANDBOX_AUTH_BASE_URL;
    }

    public function liveMode()
    {
        $this->baseUrl = JustGivingApi::PRODUCTION_BASE_URL;
        $this->authBaseUrl = JustGivingApi::PRODUCTION_AUTH_BASE_URL;
    }

    /**
     * Allows overriding of the transport mechanism.
     *
     * @param GuzzleHttp\HandlerStack Allows overriding of the handler stack.
     *   We probably won't use this in production, but it allows for mocking
     *   the HTTP transport in testing which is incredibly useful.
     */
    public function setHandlerStack($stack)
    {
        $this->stack = $stack;
    }

    /**
     * Sets up the client for performing API calls.
     *
     * @return GuzzleHttp\Client The client for making REST calls.
     */
    private function getClient()
    {
        return new Client([
            'base_uri' => $this->baseUrl . '/' . $this->apiKey . '/v' . $this->version . '/',
            'timeout' => 2.0,
            'handler' => $this->stack
        ]);
    }

    /**
     * The API is split into services, one for each endpoint to allow for building each
     * endpoint in its entirety and to make sure that we don't end up with one class with
     * 1001 methods in it.
     *
     * @return JustGivingApi\Services\EventsService The events service.
     */
    public function getEventsService()
    {
        return new EventsService($this->getClient());
    }

    public function getAccountsService()
    {
        return new AccountsService($this->getClient());
    }


    /**
     * Authentication
     */

    /**
     * Sets up the client for performing API calls.
     *
     * @return GuzzleHttp\Client The client for making REST calls.
     */
    private function getAuthClient()
    {
        return new Client([
            'base_uri' => $this->authBaseUrl . '/',
            'timeout' => 2.0,
            'handler' => $this->stack
        ]);
    }

    /**
     * Starts the authentication process by providing the URL to redirect the user to.
     *
     * @param  array $scope
     *  Array of scopes. These include openid, profile, fundraise, account, social, crowdfunding.
     *  The first two are manditory (so the function ensures this). The last two are labelled as
     *  'coming soon' in the API docs.
     * @param string $redirectUrl
     *  This is the full URL that the user will be redirected to after authenticating with JustGiving.
     *  It must match the “Home page for your application” property in 3scale app details exactly, as this is used
     *  for authentication. The URL will be called with a URL parameter of 'code' which must be used in the next
     *  call which will be to getAuthenticationToken($code).
     * @param string $guid
     *  This is a one off randomly generated value to prevent the request from getting modified. A GUID is best as it
     *  ensures uniqueness.
     * @param  string $state
     *  You can use state to allow your application to pick up where it left off, before the redirect to The Resource
     *  Server.
     * @return string The URL to redirect your user to.
     */
    public function getLoginFormUrl(array $scope, $redirectUrl, $guid, $state = '')
    {
        return $this->getAuthenticationService()->getLoginFormUrl($scope, $redirectUrl, $guid, $state);
    }

    public function getAuthenticationToken($code, $redirectUrl)
    {
        return $this->getAuthenticationService()->getAuthenticationToken($code, $redirectUrl);
    }

    protected function getAuthenticationService()
    {
        if (is_null($this->secret)) {
            throw new \RuntimeException('No OAuth secret set in JustGivingApi instance');
        }
        return new OAuth2Service($this->getAuthClient(), $this->apiKey, $this->secret);
    }
}
