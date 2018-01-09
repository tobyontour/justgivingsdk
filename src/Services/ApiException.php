<?php

namespace JustGivingApi\Exceptions;

class ApiException extends \RuntimeException
{
    public $body;

    public function __construct($response)
    {
        $statusCode = $response->getStatusCode();
        $this->body = json_decode($response->getBody());
        $url = $response->getEffectiveUrl();

        parent::__construct("Call to $url failed with status code $statusCode", $statusCode);
    }
}