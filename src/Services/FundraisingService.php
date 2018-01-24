<?php

/**
 * A service covering the /fundraising endpoints of the JustGiving API.
 */

namespace JustGivingApi\Services;

use JustGivingApi\Exceptions\ApiException;
use JustGivingApi\Models\FundraisingPage;

/**
 * Service that wraps up JustGiving API endpoints starting with /fundraising.
 */
class FundraisingService extends Service
{
    /**
     * Creates a fundraising page.
     *
     * @param  FundraisingPage $page The page data to be used to create the page
     * @return Object containing the response data:
     *  {
     *      "Next.rel": "string",
     *      "Next.uri": "string",
     *      "Next.type": "string",
     *      "Error.id": "string",
     *      "Error.desc": "string",
     *      "pageId": 0,
     *      "signOnUrl": "string",
     *      "errorMessage": "string"
     *  }
     */
    public function pageCreate(FundraisingPage $page)
    {
        try {
            $data = $this->transport->put('fundraising/pages', $page->toArray());
        } catch (ApiException $e) {
            if ($e->getCode() == 404) {
                return false;
            }
            throw $e;
        }
        return $data;
    }
}
