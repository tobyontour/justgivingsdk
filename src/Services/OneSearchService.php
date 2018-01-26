<?php

/**
 * Deals with calls to the /countries endpoint and child endpoints.
 */

namespace JustGivingApi\Services;

use JustGivingApi\Models\Query;

/**
 * Deals with calls to the /countries endpoint and child endpoints.
 */
class OneSearchService extends Service
{

    /**
     * Returns a list of allowable currency codes for use in page creation.
     *
     * @param  Query $query The query object to use for the search.
     * @return array List of types of countries in the form of and array of objects.
     *  {"currencyCode":"GBP","currencySymbol":"£","description":"British Pounds"}
     */
    public function search(Query $query)
    {
        $data = $this->transport->get('onesearch?' . (string)$query);
        return $data;
    }
}
