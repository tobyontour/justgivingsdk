<?php

/**
 * Exception for API calls.
 */

namespace JustGivingApi\Exceptions;

/**
 * Exception for API calls that nicely formats them and sets the status code.
 *
 * This exception is there to be thrown in the case of an error that comes back
 * from the API with regards to a call. So, for example, if you tried to create
 * a user that was already there or made a call that required authentication but
 * hadn't provided it then you'll get an ApiException. It's a child of the
 * RuntimeException and adds a public property called $body that contains the
 * body of the response if any (the JustGIving API docs show what the content of
 * an error would be - it's specific to call that's been made). It also sets the
 * error code of the exception to the HTTP response code.
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
        $this->body = $response->getBody()->getContents();

        parent::__construct("Call to $url failed with status code $statusCode.", $statusCode);
    }
}
