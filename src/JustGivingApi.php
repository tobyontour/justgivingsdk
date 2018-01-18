<?php

/**
 * Main class for making requests to the JustGiving API.
 */

namespace JustGivingApi;

use JustGivingApi\Services\EventsService;
use JustGivingApi\Services\AccountsService;
use JustGivingApi\Services\FundraisingService;
use JustGivingApi\Services\TeamService;
use JustGivingApi\Services\OAuth2Service;
use JustGivingApi\Transport\Transport;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\CurlHandler;

/**
 * The format of dates in JustGiving.
 */
const JUSTGIVINGAPI_DATE_FORMAT = 'Y-m-d\TH:i:s.000\Z';

/**
 * Main class for making requests to the JustGiving API.
 */
class JustGivingApi
{
    /**
     * The API key from JustGiving. This is specific to your app.
     * @var string
     */
    private $apiKey;

    /**
     * The OAuth secret for your app. Only needed for authenticated requests.
     * @var [type]
     */
    private $secret;

    /**
     * The base URL of all normal endpoint requests.
     * @var string
     */
    private $baseUrl;

    /**
     * The base URL of authentication requests.
     * @var string
     */
    private $authBaseUrl;

    /**
     * The API version to use.
     * @var int
     */
    private $version;

    /**
     * The handler that is used to make requests.
     * @var CurlHandler
     */
    private $handler;

    /**
     * Instance of the Transport class that makes REST calls.
     * @var Transport
     */
    private $transport;

    const SANDBOX_BASE_URL = 'https://api.sandbox.justgiving.com';
    const PRODUCTION_BASE_URL = 'https://api.justgiving.com';
    const SANDBOX_AUTH_BASE_URL = 'https://identity.sandbox.justgiving.com';
    const PRODUCTION_AUTH_BASE_URL = 'https://identity.justgiving.com';
    const TIMEOUT = 4.0;

    /**
     * Creates an instance of the API.
     *
     * @param string $apiKey The JustGiving API key. Register as a developer on the website to get this.
     * @param string $secret The API secret. Needed for authenticated requests on behalf of the user.
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

    /**
     * Uses the test endpoints.
     */
    private function testMode()
    {
        $this->baseUrl = JustGivingApi::SANDBOX_BASE_URL;
        $this->authBaseUrl = JustGivingApi::SANDBOX_AUTH_BASE_URL;
    }

    /**
     * Uses the live endpoints.
     */
    private function liveMode()
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
    public function getTransport()
    {
        if (is_null($this->transport)) {
            $this->transport = new Transport(new Client([
                'base_uri' => $this->baseUrl . '/' . $this->apiKey . '/v' . $this->version . '/',
                'timeout' => self::TIMEOUT,
                'handler' => $this->stack
            ]));
        }
        return $this->transport;
    }

    /**
     * Sets the access token to use to authenticate as a user.
     *
     * @param string $accessToken The access token retrieved via getAuthenticationToken().
     */
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
     * Gets and instance of the AccountsService.
     *
     * @return JustGivingApi\Services\AccountsService The accounts service.
     */
    public function getAccountsService()
    {
        return new AccountsService($this->getTransport());
    }

    /**
     * Gets the fundraising service.
     *
     * @return JustGivingApi\Services\FundraisingService The fundraising service.
     */
    public function getFundraisingService()
    {
        return new FundraisingService($this->getTransport());
    }

    /**
     * Gets the team service.
     *
     * @return JustGivingApi\Services\TeamService The team service.
     */
    public function getTeamService()
    {
        return new TeamService($this->getTransport());
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
            'timeout' => self::TIMEOUT,
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

    /**
     * Retrieves an authentication token to act on bhalf of a user.
     *
     * @param string $code The code returned as a parameter to the redirect URL. See getLoginFormUrl().
     * @param string $redirectUrl The redirect URL. See docs for getLoginFormUrl().
     * @return object Object containing access_token and (optionally) refresh_token
     */
    public function getAuthenticationToken($code, $redirectUrl)
    {
        return $this->getAuthenticationService()->getAuthenticationToken($code, $redirectUrl);
    }

    /**
     * Refreshes the authentication token if it has expired.
     *
     * @param string $refreshToken The refresh token. This will have come from the getAuthenticationToken() call.
     * @param string $redirectUrl The redirect URL. See docs for getLoginFormUrl().
     * @return object Object containing access_token and refresh_token
     */
    public function refreshAuthenticationToken($refreshToken, $redirectUrl)
    {
        return $this->getAuthenticationService()->refreshAuthenticationToken($refreshToken, $redirectUrl);
    }

    /**
     * Gets an instance of the OAuth2Service.
     *
     * @return OAuth2Service An instance of the OAuth2Service.
     */
    protected function getAuthenticationService()
    {
        if (is_null($this->secret)) {
            throw new \RuntimeException('No OAuth secret set in JustGivingApi instance');
        }
        return new OAuth2Service($this->getAuthTransport(), $this->apiKey, $this->secret);
    }
}
