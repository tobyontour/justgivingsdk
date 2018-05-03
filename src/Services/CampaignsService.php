<?php

/**
 * Deals with calls to the /campaigns endpoint and child endpoints.
 */

namespace JustGivingApi\Services;

use JustGivingApi\Models\Campaign;

/**
 * Deals with calls to the /campaigns endpoint and child endpoints.
 */
class CampaignsService extends Service
{
    /**
     * Get campaigns for a charity.
     *
     * @return array List of Campaigns
     */
    public function getCampaignsByCharityId($charityId)
    {
        $data = $this->transport->get('campaigns/' . $charityId, true);
        $ret = array();
        foreach ($data['campaignsDetails'] as $key => $value) {
            $ret[$key] = new Campaign($value);
        }
        return $ret;
    }

    /**
     * Create a user account.
     *
     * @param Campaign $campaign The campaign object to create.
     * @return Object containing the response data:
     *  {
     *    "charityShortName": "string",
     *    "campaignShortUrl": "string",
     *    "campaignFullUrl": "string",
     *    "errorMessage": "string"
     *. }
     */
    public function createCampaign(Campaign $campaign)
    {
        try {
            $data = $this->transport->post('campaigns', $campaign->toArray());
        } catch (ApiException $e) {
            if ($e->getCode() == 404) {
                return false;
            }
            throw $e;
        }
        return $data;
    }
}
