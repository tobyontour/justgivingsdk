<?php

/**
 * Exception for API calls.
 */

namespace JustGivingApi\Exceptions;

/**
 * Exception for API calls that nicely formats them and sets the status code.
 */
class ApiException extends \RuntimeException
{
    /**
     * The raw body of the response.
     * @var string
     */
    public $body;

    /**
     * Constructor.
     *
     * @param \GuzzleHttp\Psr7\Response $response The response from the Guzzle call.
     * @param string $url The URL that was called.
     */
    public function __construct($response, $url)
    {
        $statusCode = $response->getStatusCode();
        $this->body = $response->getBody();

        parent::__construct("Call to $url failed with status code $statusCode.", $statusCode);
    }
}
