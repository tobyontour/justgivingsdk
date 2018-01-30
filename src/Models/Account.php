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
    public $address = [];
    public $email;
    public $password;
    public $acceptTermsAndConditions = false;
    public $causeId;

    // retrieve only.
    public $activePageCount;
    public $completedPagesCount;
    public $accountTypes;
    public $totalDonated;
    public $totalGiftAid;
    public $totalRaised;
    public $joinDate;
    public $userId;
    public $accountId;
    public $country;
    public $donationTotalsInSupportedCurrencies;
    public $profileImageUrls;
    public $raisedTotalsInSupportedCurrencies;
    public $totalDonatedGiftAid;
    public $town;
}
