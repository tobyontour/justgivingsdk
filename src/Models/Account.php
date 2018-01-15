<?php

/**
 * Class representing a JustGiving account
 */

namespace JustGivingApi\Models;

/**
 * Class representing a JustGiving account
 */
class Account
{
    public $reference;
    public $title;
    public $firstName;
    public $lastName;
    public $addressLine1;
    public $addressLine2;
    public $addressTownOrCity;
    public $addressCountyOrState;
    public $addressCountry;
    public $addressPostcodeOrZipcode;
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

    /**
     * Constructor.
     *
     * @param array An array to prepopulate the event with, often from a JSON decode response.
     */
    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            $key = preg_replace('/^Address\./', 'address', $key);
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Convert the object to an array.
     *
     * @return array The array to send as part of a REST request.
     */
    public function toArray()
    {
        $arr = array();
        foreach (array_keys(get_class_vars('JustGivingApi\Models\Account')) as $var) {
            $key = preg_replace('/^address/', 'Address.', $var);
            if (!is_null($this->$key)) {
                $arr[$key] = $this->$var;
            }
        }
        return $arr;
    }
}
