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
}
