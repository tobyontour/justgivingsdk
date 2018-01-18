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
    public $name;
    public $teamShortName;
    public $story;
    public $teamTarget;
    public $targetCurrency;
    public $TeamImages_TeamLogo_url;
    public $TeamImages_TeamPhoto_url;
    public $errorMessage;
}
