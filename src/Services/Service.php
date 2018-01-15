<?php

/**
 * Base class for services.
 */

namespace JustGivingApi\Services;

use JustGivingApi\Exceptions\ApiException;
use GuzzleHttp\Exception\ClientException;

/**
 * Base class for services.
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
