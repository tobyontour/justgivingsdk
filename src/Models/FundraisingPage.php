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
    public $consistentErrorResponses;
    public $teamId;
    public $currency;
    public $theme;
    public $rememberedPersonReference;
}
