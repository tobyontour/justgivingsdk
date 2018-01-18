<?php

/**
 * Class representing a JustGiving account
 */

namespace JustGivingApi\Models;

/**
 * Class representing a JustGiving account
 */
class Account extends Model
{
    public $reference;
    public $title;
    public $firstName;
    public $lastName;
    public $Address_Line1;
    public $Address_Line2;
    public $Address_TownOrCity;
    public $Address_CountyOrState;
    public $Address_Country;
    public $Address_PostcodeOrZipcode;
    public $email;
    public $password;
    public $acceptTermsAndConditions = false;
    public $causeId;

    public $activePageCount;
    public $completedPageCount;
    public $totalDonated;
    public $totalGiftAid;
    public $totalRaised;
    public $joinDate;
    public $userId;
}
