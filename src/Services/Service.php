<?php

namespace JustGivingApi\Services;

class Service
{
    protected $client;

    public function __construct($client)
    {
        $this->client = $client;
    }

    protected function get($path)
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
            throw new \RuntimeException('Call to ' . $path . ' failed with status code ' . $response->getStatusCode());
        }

        return json_decode($response->getBody());
    }
}