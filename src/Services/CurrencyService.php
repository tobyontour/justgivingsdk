<?php

/**
 * Deals with calls to the /countries endpoint and child endpoints.
 */

namespace JustGivingApi\Services;

/**
 * Deals with calls to the /countries endpoint and child endpoints.
 */
class CurrencyService extends Service
{
    /**
     * Returns a list of allowable currency codes for use in page creation.
     *
     * @return array List of types of countries in the form of and array of objects.
     *  {"currencyCode":"GBP","currencySymbol":"£","description":"British Pounds"}
     */
    public function getCurrencies()
    {
        $data = $this->transport->get('fundraising/currencies');
        return $data;
    }
}
