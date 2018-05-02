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
    public $campaignPageName;
    public $campaignUrl;
    public $causeId;
    public $charityId;
    public $charityLogoUrl;
    public $currency;
    public $description;
    public $fundraisingEnabled;
    public $id;
    public $images;
    public $numberOfDirectDonations;
    public $numberOfFundraisersConnected;
    public $story;
    public $target;
    public $targetPercentage;
    public $totalDonated;
    public $totalFundraised;
    public $totalOffline;
    public $totalRaised;
}
