<?php

/**
 * Base class for services.
 */

namespace JustGivingApi\Services;

use JustGivingApi\Exceptions\ApiException;
use GuzzleHttp\Exception\ClientException;

/**
 * Base class for services.
 *
 * The Service class simply takes in a Transport instance which it uses to perform the REST calls.
 * The Transport class is a thin wrapper around a Guzzle Client class (Guzzle being a PHP HTTP
 * client). The idea is that the Transport class can be set up with timeouts and any network
 * configuration or authentication methods so that the instances of Services don't have to do that
 * themselves.
 */
class Service
{
    /**
     * The class that allows for performing HTTP requests such as GET, POST, PUT etc.
     *
     * @var \JustGivingApi\Transport\Transport
     */
    protected $transport;

    /**
     * Constructor.
     *
     * @param \JustGivingApi\Transport\Transport $transport The transport that performs HTTP requests.
     */
    public function __construct(\JustGivingApi\Transport\Transport $transport)
    {
        $this->transport = $transport;
    }
}
