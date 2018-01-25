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

    // These may well be arrays.
    public $CustomCodes_customCode1;
    public $CustomCodes_customCode2;
    public $CustomCodes_customCode3;
    public $CustomCodes_customCode4;
    public $CustomCodes_customCode5;
    public $CustomCodes_customCode6;
    public $Theme_pageBackground;
    public $Theme_buttonsThermometerFill;
    public $Theme_linesThermometerBackground;
    public $Theme_backgroundColour;
    public $Theme_buttonColour;
    public $Theme_titleColour;
    public $RememberedPersonReference_relationship;
    public $RememberedPersonReference_RememberedPerson_id;
    public $RememberedPersonReference_RememberedPerson_firstName;
    public $RememberedPersonReference_RememberedPerson_lastName;
    public $RememberedPersonReference_RememberedPerson_town;
    public $RememberedPersonReference_RememberedPerson_dateOfBirth;
    public $RememberedPersonReference_RememberedPerson_dateOfDeath;
    public $RememberedPersonReference_RememberedPerson_errorMessage;
    public $RememberedPersonReference_errorMessage;
}
