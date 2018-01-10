<?php

namespace JustGivingApi;

use JustGivingApi\Services\EventsService;
use JustGivingApi\Services\AccountsService;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\CurlHandler;

const JUSTGIVINGAPI_DATE_FORMAT = 'Y-m-d\TH:i:s.000\Z';

class JustGivingApi
{
    private $apiKey;
    private $baseUrl;
    private $version;
    private $handler;

    const SANDBOX_BASE_URL = 'https://api.sandbox.justgiving.com';
    const PRODUCTION_BASE_URL = 'https://api.justgiving.com';

    /**
     * Creates an instance of the API.
     *
     * @param string The JustGiving API key. Register as a developer on the website to get this.
     * @param string The base URL. Use one of the class constants (unless there's a reason to do otherwise). Defaults to production.
     * @param integer API version.
     */
    public function __construct($apiKey, $baseUrl = JustGivingApi::PRODUCTION_BASE_URL, $version = 1)
    {
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
        $this->version = $version;
        $this->handler = new CurlHandler();
        $this->stack = HandlerStack::create($this->handler);
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
}