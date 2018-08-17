<?php

/**
 * Class representing a JustGiving campaign
 */

namespace JustGivingApi\Models;

/**
 * Class representing a JustGiving campaign
 */
class Campaign extends Model
{
    // Set.
    public $campaignDeadline; // Only on set.
    public $campaignThankYouMessage; // Only on set.
    public $campaignLogos; // Only on set.
    public $campaignCoverPhotos; // Only on set.
    public $campaignPhotos; // Only on set.

    // Set and get.
    public $campaignPageName;
    public $campaignUrl;
    public $currency;
    public $target;
    public $fundraisingEnabled;
    public $story;
    public $summary;

    // Get.
    public $causeId;
    public $charityId;
    public $charityLogoUrl;
    public $description;
    public $id;
    public $images;
    public $numberOfDirectDonations;
    public $numberOfFundraisersConnected;
    public $targetPercentage;
    public $totalDonated;
    public $totalFundraised;
    public $totalOffline;
    public $totalRaised;

    /**
     * Convert the object to an array for set actions.
     *
     * @param  array $omitList List of properties to omit.
     * @return array The array to send as part of a REST request.
     */
    public function toArray($omitList = [])
    {
        $omitList = array_merge($omitList, [
            'causeId',
            'charityId',
            'charityLogoUrl',
            'description',
            'id',
            'images',
            'numberOfDirectDonations',
            'numberOfFundraisersConnected',
            'story',
            'targetPercentage',
            'totalDonated',
            'totalFundraised',
            'totalOffline',
            'totalRaised',
        ]);
        $ret = parent::toArray($omitList);
        $ret['campaignName'] = $this->campaignPageName;
        $ret['campaignStory'] = $this->story;
        $ret['campaignSummary'] = $this->summary;
        $ret['currencyCode'] = $this->currency;
        $ret['campaignTarget'] = $this->target;

        unset(
            $ret['campaignPageName'],
            $ret['story'],
            $ret['summary'],
            $ret['currency'],
            $ret['target']
        );
        return $ret;
    }
}
