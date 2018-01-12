<?php

namespace JustGivingApi\Services;

use JustGivingApi\Exceptions\ApiException;
use GuzzleHttp\Exception\ClientException;

class Service
{
    /**
     * @var \JustGivingApi\Transport\Transport
     */
    protected $transport;

    public function __construct(\JustGivingApi\Transport\Transport $transport)
    {
        $this->transport = $transport;
    }
}
