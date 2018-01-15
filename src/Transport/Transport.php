<?php

/**
 * Transport class that performs REST requests.
 */

namespace JustGivingApi\Transport;

use JustGivingApi\Exceptions\ApiException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Client;

/**
 * A class allowing for REST requests.
 */
class Transport
{
    /**
     * The Guzzle client to use for requests. The base url should be set.
     *
     * @var GuzzleHttp\Client
     */
    protected $client;

    /**
     * The basic auth credentials.
     *
     * @var array|null username and password array or null if not set.
     */
    protected $basicAuth = null;

    /**
     * The headers to send with every request.
     *
     * @var array
     */
    public $headers = ['Accept' => 'application/json'];

    /**
     * Constructor.
     *
     * @param Client The Guzzle client to use.
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Returns the base url of requests.
     *
     * @return string The base URL for requests.
     */
    public function getBaseUrl()
    {
        return $this->client->getConfig('base_uri');
    }

    /**
     * Sets the credentials for Basic Auth and turns it on.
     *
     * @param string $username The username.
     * @param string $password The password.
     */
    public function setBasicAuth($username, $password)
    {
        $this->basicAuth = [
            'username' => $user,
            'password' => $password
        ];
    }

    /**
     * Disable basic auth.
     */
    public function disableBasicAuth()
    {
        $this->basicAuth = null;
    }

    /**
     * HTTP GET request.
     *
     * @param  string $path The path to get from.
     * @param  bool|boolean $assoc Whether to return an associative array or not.
     * @return mixed object or array depending on the value of $assoc
     */
    public function get($path, $assoc = false)
    {
        try {
            $options = array(
                'headers' => [
                   'Accept'     => 'application/json'
                ]
            );

            $response = $this->client->request('GET', $path, $options);
        } catch (ClientException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
            } else {
                throw new \RuntimeException('Call to ' . $path . ' failed with ' . $e->getMessage());
            }
        }

        if ($response->getStatusCode() >= 300) {
            throw new ApiException($response, $path);
        }

        return json_decode($response->getBody(), $assoc);
    }

    /**
     * HTTP POST request.
     *
     * @param  string $path The path to send the request to.
     * @param  array $body The body of the POST. It will be encoded into JSON for you.
     * @param  boolean $assoc If true will return an associative array, the default is false to return an object.
     * @param  boolean $sendAsFormEncoded If true the $body array will be sent as form elements instead.
     * @return mixed object or array depending on the value of $assoc
     */
    public function post($path, $body = array(), $assoc = false, $sendAsFormEncoded = false)
    {
        try {
            $options = array(
                'headers' => $this->headers
            );

            if (!is_null($this->basicAuth)) {
                $options['auth'] = array($this->basicAuth['username'], $this->basicAuth['password']);
            }

            if ($sendAsFormEncoded) {
                $options['headers']['Content-Type'] = 'application/x-www-form-urlencoded';
                $options['form_params'] = $body;
            } else {
                $options['body'] = json_encode($body);
            }

            $response = $this->client->request('POST', $path, $options);
        } catch (\Exception $e) {
            throw new \RuntimeException('Call to ' . $path . ' failed with ' . $e->getMessage());
        }

        if ($response->getStatusCode() >= 300) {
            throw new \ApiException($response, $path);
        }

        return json_decode($response->getBody(), $assoc);
    }

    /**
     * HTTP PUT request.
     *
     * @param  string $path The path to send the request to.
     * @param  array The body of the PUT. It will be encoded into JSON for you.
     * @param  boolean If true will return an associative array, the default is false to return an object.
     * @return mixed object or array depending on the value of $assoc
     */
    public function put($path, $body = array(), $assoc = false)
    {
        try {
            $options = [
               'headers' => [
                   'Accept'     => 'application/json'
               ],
               'body' => json_encode($body)
            ];

            $response = $this->client->request('PUT', $path, $options);
        } catch (\Exception $e) {
            throw new \RuntimeException('Call to ' . $path . ' failed with ' . $e->getMessage());
        }

        if ($response->getStatusCode() >= 300) {
            throw new \ApiException($response, $path);
        }

        return json_decode($response->getBody(), $assoc);
    }
}
