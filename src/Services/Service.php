<?php

namespace JustGivingApi\Services;

use JustGivingApi\Exceptions\ApiException;
use GuzzleHttp\Exception\ClientException;

class Service
{
    protected $client;

    public function __construct($client)
    {
        $this->client = $client;
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
            $response = $this->client->request('GET', $path, [
               'headers' => [
                   'Accept'     => 'application/json'
               ]
            ]);
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
            $headers = array(
                'Accept' => 'application/json'
            );
            if ($sendAsFormEncoded) {
                $response = $this->client->request('POST', $path, [
                    'headers' => $headers,
                    'form_params' => $body
                ]);
            } else {
                $response = $this->client->request('POST', $path, [
                    'headers' => $headers,
                    'body' => json_encode($body)
                ]);
            }
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
            $response = $this->client->request('PUT', $path, [
               'headers' => [
                   'Accept'     => 'application/json'
               ],
               'body' => json_encode($body)
            ]);
        } catch (\Exception $e) {
            throw new \RuntimeException('Call to ' . $path . ' failed with ' . $e->getMessage());
        }

        if ($response->getStatusCode() >= 300) {
            throw new \ApiException($response, $path);
        }

        return json_decode($response->getBody(), $assoc);
    }
}
