<?php

/**
 * Deals wioth calls to the /countries endpoint and child endpoints.
 */

namespace JustGivingApi\Services;

/**
 * Deals with calls to the /countries endpoint and child endpoints.
 */
class CountriesService extends Service
{
    /**
     * GA list of allowable countries for use when registering a new user account. You can use either the country
     * name or its corresponding ISO 3166-1 two-letter country code.
     *
     * @return array List of types of countries in the form of and array of objects.
     *  {
     *    "countryCode": "AF",
     *    "name": "Afghanistan"
     *  }
     */
    public function listCountries()
    {
        $data = $this->transport->get('countries');
        return $data;
    }
}
