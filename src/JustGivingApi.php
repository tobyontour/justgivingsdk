<?php

namespace JustGivingApi;

use JustGivingApi\Services\EventsService;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\CurlHandler;

class JustGivingApi
{
    private $apiKey;
    private $baseUrl;
    private $version;
    private $handler;

    const SANDBOX_BASE_URL = 'https://api.sandbox.justgiving.com';
    const PRODUCTION_BASE_URL = 'https://api.justgiving.com';

    public function __construct($baseUrl, $apiKey, $version = 1)
    {
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
        $this->version = $version;
        $this->handler = new CurlHandler();
        $this->stack = HandlerStack::create($this->handler);
    }
    
    public function setHandlerStack($stack)
    {
        $this->stack = $stack;
    }

    private function getClient()
    {
        return new Client([
            'base_uri' => $this->baseUrl . '/' . $this->apiKey . '/v' . $this->version . '/',
            'timeout' => 2.0,
            'handler' => $this->stack
        ]);
    }

    public function getEventsService()
    {
        return new EventsService($this->getClient());
    }
}