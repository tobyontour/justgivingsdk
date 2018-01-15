<?php

namespace JustGivingApi;

use JustGivingApi\Services\EventsService;
use JustGivingApi\Services\AccountsService;
use JustGivingApi\Services\OAuth2Service;
use JustGivingApi\Transport\Transport;
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

    private $transport;

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
     * This must be called before getting any services.
     *
     * @param GuzzleHttp\HandlerStack Allows overriding of the handler stack.
     *   We probably won't use this in production, but it allows for mocking
     *   the HTTP transport in testing which is incredibly useful.
     */
    public function setHandlerStack($stack)
    {
        if (!is_null($this->transport)) {
            // Fail if we already have a transport as we can't safely reconfigure an existing one
            // or recreate it.
            throw new \RuntimeException('setHandlerStack must be called before getTransport.');
        }
        $this->stack = $stack;
    }

    /**
     * Sets up the transport for performing API calls.
     *
     * @return JustGivingApi\Transport\Transport The client for making REST calls.
     */
    private function getTransport()
    {
        if (is_null($this->transport)) {
            $this->transport = new Transport(new Client([
                'base_uri' => $this->baseUrl . '/' . $this->apiKey . '/v' . $this->version . '/',
                'timeout' => 2.0,
                'handler' => $this->stack
            ]));
        }
        return $this->transport;
    }

    public function setAccessToken($accessToken)
    {
        $transport = $this->getTransport();
        $transport->headers['x-application-key'] = $this->secret;
        $transport->headers['Authorization'] = 'Bearer ' . $accessToken;
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
        return new EventsService($this->getTransport());
    }

    /**
     * @return JustGivingApi\Services\AccountsService The accounts service.
     */
    public function getAccountsService()
    {
        return new AccountsService($this->getTransport());
    }

    /**
     * Authentication
     */

    /**
     * Sets up the client for performing API calls.
     *
     * @return JustGivingApi\Transport\Transport The client for making REST calls.
     */
    private function getAuthTransport()
    {
        return new Transport(new Client([
            'base_uri' => $this->authBaseUrl . '/',
            'timeout' => 2.0,
            'handler' => $this->stack
        ]));
    }

    /**
     * Starts the authentication process by providing the URL to redirect the user to.
     *
     * @param  array $scope
     *  Array of scopes. These include openid, profile, fundraise, account, social, crowdfunding, and offline_access.
     *  The first two are manditory (so the function ensures this). The last two are labelled as
     *  'coming soon' in the API docs.
     *  You *must* include the scope 'offline_access' if you want to be able to use the token beyond the normal timeout
     *  which is about an hour.
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

    public function refreshAuthenticationToken($refreshToken, $redirectUrl)
    {
        return $this->getAuthenticationService()->refreshAuthenticationToken($refreshToken, $redirectUrl);
    }

    protected function getAuthenticationService()
    {
        if (is_null($this->secret)) {
            throw new \RuntimeException('No OAuth secret set in JustGivingApi instance');
        }
        return new OAuth2Service($this->getAuthTransport(), $this->apiKey, $this->secret);
    }
}
