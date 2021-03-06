<?php

/**
 * Class that encapsulates a onesearch query.
 */

namespace JustGivingApi\Models;

/**
 * Class that encapsulates a onesearch query.
 */
class Query
{
    /**
     * Your search term or terms
     * @var string
     */
    public $query = '';

    /**
     * Allows you to group search results by index
     * @var string
     */
    public $group = '';

    /**
     * Narrow search results by index: Charity, Event, Fundraiser, Globalproject, Crowdfunding
     * @var string
     */
    public $index = '';

    /**
     * Maximum number of search results to return
     * @var int
     */
    public $limit = null;

    /**
     * The result paging offset
     * @var int
     */
    public $offset = 0;

    /**
     * Two letter ISO country code for localised results
     * @var string
     */
    public $country = '';

    /**
     * Whether to include additional debug information or not.
     * @var string
     */
    public $debug = '';

    /**
     * The charity id to filter on (only filters fundraiser, event results).
     * @var string
     */
    public $charityId = '';

    /**
     * The event id to filter on (only filters fundraiser results).
     * @var int
     */
    public $eventId = null;

    /**
     * The campaign id to filter on (only filters fundraiser results).
     * @var int
     */
    public $campaignId = null;

    /**
     * The company appeal id to filter on (only filters fundraiser results).
     * @var int
     */
    public $companyAppealId = null;

    /**
     * The cause id to filter on. Note: if included, this overrides any filtering on
     * campaignId (only filters fundraiser results).
     *
     * @var int
     */
    public $causeId = null;

    /**
     * Constructor.
     *
     * @param string $query Your search term or terms.
     */
    public function __construct($query = '')
    {
        $this->query = $query;
    }

    /**
     * Returns the query as a formatted URL query string.
     *
     * @return string The query string. No leading '?''
     */
    public function __toString()
    {
        $params = [];

        // Get all the properties of the class.
        foreach (array_keys(get_class_vars(get_class($this))) as $var) {
            $key = $var;

            // Just use the first initial of query, group, and index.
            if (in_array($var, ['query', 'group', 'index'])) {
                $key = $var[0];
            }
            if (!empty($this->$var)) {
                $params[$key] = $this->$var;
            }
        }
        return http_build_query($params);
    }
}
