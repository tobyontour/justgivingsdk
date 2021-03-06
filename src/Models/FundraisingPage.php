<?php

/**
 * Class that encapsulates a fundraising page in JustGiving.
 */

namespace JustGivingApi\Models;

/**
 * Class that encapsulates a fundraising page in JustGiving.
 */
class FundraisingPage extends Model
{
    public $reference;
    public $charityId;
    public $eventId;
    public $pageShortName;
    public $pageTitle;
    public $activityType;
    public $targetAmount;
    public $charityOptIn;
    public $eventDate;
    public $eventName;
    public $attribution;
    public $charityFunded;
    public $causeId;
    public $companyAppealId;
    public $expiryDate;
    public $pageStory;
    public $pageSummaryWhat;
    public $pageSummaryWhy;
    public $customCodes;
    public $consistentErrorResponses;
    public $teamId;
    public $currency;
    public $theme;
    public $rememberedPersonReference;

    // Get only
    public $pageId;
    public $activityCharityCreated;
    public $activityId;
    public $currencySymbol;
    public $image;
    public $status;
    public $owner;
    public $ownerProfileImageUrls;
    public $title;
    public $showEventDate;
    public $showExpiryDate;
    public $fundraisingTarget;
    public $totalRaisedPercentageOfFundraisingTarget;
    public $totalRaisedOffline;
    public $totalRaisedOnline;
    public $totalRaisedSms;
    public $totalEstimatedGiftAid;
    public $branding;
    public $charity;
    public $media;
    public $story;
    public $domain;
    public $smsCode;
    public $rememberedPersonSummary;
    public $teams;
    public $pageSummary;
}
