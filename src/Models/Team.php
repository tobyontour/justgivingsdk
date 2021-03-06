<?php

/**
 * Class that encapsulates what makes a team in JustGiving.
 */

namespace JustGivingApi\Models;

/**
 * Class that encapsulates what makes a team in JustGiving.
 */
class Team extends Model
{
    // Common to set and get.
    public $name;
    public $teamShortName;
    public $story;
    public $teamTarget;
    public $targetCurrency;
    public $teamImages;
    public $errorMessage;

    // Get only.
    public $dateCreated;
    public $id;
    public $localCurrencySymbol;
    public $currencySymbol;
    public $targetType;
    public $teamMembers;
    public $teamType;
    public $raisedSoFar;

    /**
     * Convert the object to an array.
     *
     * @param  array $omitList List of properties to omit.
     * @return array The array to send as part of a REST request.
     */
    public function toArray($omitList = [])
    {
        $omitList = array_merge($omitList, [
            'dateCreated',
            'id',
            'localCurrencySymbol',
            'currencySymbol',
            'targetType',
            'teamMembers',
            'teamType',
            'raisedSoFar'
        ]);
        return parent::toArray($omitList);
    }
}
