<?php

namespace JustGivingApi\Services;

use JustGivingApi\Exceptions\ApiException;

class Service
{
    protected $client;

    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
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
        } catch (\Exception $e) {
            throw new \RuntimeException('Call to ' . $path . ' failed with ' . $e->getMessage());
        }

        if ($response->getStatusCode() >= 300) {
            throw new \ApiException($response);
        }

        return json_decode($response->getBody(), $assoc);
    }

    /**
     * @param  string $path The path to send the request to.
     * @param  array The body of the POST. It will be encoded into JSON for you.
     * @param  boolean If true will return an associative array, the default is false to return an object.
     * @return mixed object or array depending on the value of $assoc
     */
    protected function post($path, $body = array(), $assoc = false)
    {
        try {
            $response = $this->client->request('POST', $path, [
               'headers' => [
                   'Accept'     => 'application/json'
               ],
               'body' => json_encode($body)
            ]);
        } catch (\Exception $e) {
            throw new \RuntimeException('Call to ' . $path . ' failed with ' . $e->getMessage());
        }

        if ($response->getStatusCode() >= 300) {
            throw new \ApiException($response);
        }

        return json_decode($response->getBody(), $assoc);
    }
}