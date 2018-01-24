<?php

/**
 * Deals wioth calls to the /team endpoint and child endpoints.
 */

namespace JustGivingApi\Services;

use JustGivingApi\Models\Team;

/**
 * Deals wioth calls to the /team endpoint and child endpoints.
 */
class TeamService extends Service
{
    /**
     * Create a team.
     *
     * @param  Team The team to create.
     * @return object Contains
     *
     */
    public function createTeam(Team $team)
    {
        $path = 'team';

        $data = $this->transport->put($path, $team->toArray());

        return $data;
    }

    /**
     * Retrieve the details of an existing team.
     *
     * @param string $teamName The short name of the team.
     */
    public function getTeam($teamName)
    {
        $data = $this->transport->get('team/' . $teamName, true);

        return new Team((array)$data);
    }
}
