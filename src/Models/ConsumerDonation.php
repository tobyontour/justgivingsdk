<?php

/**
 * ConsumerDonation class that contains the data from a donation
 */

namespace JustGivingApi\Models;

/**
 * ConsumerDonation class that contains the data from a donation
 */
class ConsumerDonation extends Model
{
    public $amount;
    public $currencyCode;
    public $donationDate;
    public $donationRef;
    public $donorDisplayName;
    public $donorLocalAmount;
    public $donorLocalCurrencyCode;
    public $estimatedTaxReclaim;
    public $id;
    public $image;
    public $message;
    public $source;
    public $status;
    public $thirdPartyReference;
    public $charityId;
    public $charityName;
    public $logoAbsoluteUrl;
    public $ownerProfileImageUrls;
    public $pageOwnerName;
    public $pageShortName;
    public $pageTitle;
    public $paymentType;
}
