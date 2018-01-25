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
    public function createPage(FundraisingPage $page)
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

    /**
     * Get a page update by its ID.
     *
     * @param  string $pageShortName The page name
     * @param  int $updateId The ID of the update
     * @return object Containing the Id, Video, CreatedDate, and Message
     */
    public function getPageUpdateById($pageShortName, $updateId)
    {
        return $this->transport->get('fundraising/pages/' . $pageShortName . '/updates/' . $updateId);
    }

    /**
     * Get a page's updates.
     *
     * @param  string $pageShortName The page name
     * @return object Containing the Id, Video, CreatedDate, and Message
     */
    public function getPageUpdates($pageShortName)
    {
        return $this->transport->get('fundraising/pages/' . $pageShortName . '/updates');
    }
}
