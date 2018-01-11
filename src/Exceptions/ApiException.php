<?php

namespace JustGivingApi\Exceptions;

class ApiException extends \RuntimeException
{
    public $body;

    public function __construct($response, $url)
    {
        $statusCode = $response->getStatusCode();
        $this->body = json_decode($response->getBody());

        parent::__construct("Call to $url failed with status code $statusCode", $statusCode);
    }
}
