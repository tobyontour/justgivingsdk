<?php

namespace JustGivingApi\Services;

use JustGivingApi\Exceptions\ApiException;
use GuzzleHttp\Exception\ClientException;

class Service
{
    protected $client;
    protected $basicAuth = null;

    public function __construct($client)
    {
        $this->client = $client;
    }

    public function setBasicAuth($username, $password)
    {
        $this->basicAuth = [
            'username' => $user,
            'password' => $password
        ];
    }

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
    protected function get($path, $assoc = false)
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
    protected function post($path, $body = array(), $assoc = false, $sendAsFormEncoded = false)
    {
        try {
            $options = array(
                'headers' => [
                   'Accept'     => 'application/json'
                ]
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
    protected function put($path, $body = array(), $assoc = false)
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
