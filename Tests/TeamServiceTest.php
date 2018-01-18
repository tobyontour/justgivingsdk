<?php

namespace JustGivingApi\Tests;

use PHPUnit\Framework\TestCase;
use JustGivingApi\JustGivingApi;
use JustGivingApi\Models\Team;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;

class TeamServiceTest extends TestCase
{
    protected function getMockHandlerStack(&$container, array $responses = [])
    {
        $container = [];

        $history = Middleware::history($container);
        $mock = new MockHandler($responses);
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);
        return $handlerStack;
    }

    public function testTeamCreation()
    {
        $api = new JustGivingApi('API_KEY', null, true);

        $handlerStack = $this->getMockHandlerStack($container, [
            new Response(200, [], '{"accountType":"None"}') // Need actual content from a call.
        ]);

        $api->setHandlerStack($handlerStack);

        $teamService = $api->getTeamService();

        $this->assertEquals(
            'JustGivingApi\Services\TeamService',
            get_class($teamService)
        );

        $teamArray = [
            'name' => 'The A-Team',
            'teamShortName' => 'a-team',
            'story' => 'This is the story of how my life got flipped turned upside-down.',
            'teamTarget' => 3500,
            'targetCurrency' => 'GBP',
            'TeamImages.TeamLogo.url' => 'http://example.com/1.jpg',
            'TeamImages.TeamPhoto.url' => 'http://example.com/2.jpg'
        ];

        $team = new Team($teamArray);

        $result = $teamService->createTeam($team);

        $uri = $container[0]['request']->getUri();

        $this->assertEquals(
            JustGivingApi::SANDBOX_BASE_URL . '/API_KEY/v1/team',
            (string)$uri
        );

        $this->assertEquals(
            'PUT',
            $container[0]['request']->getMethod()
        );

        $body = json_decode($container[0]['request']->getBody(), true);

        $this->assertEquals(
            0,
            count(array_diff_assoc($body, $teamArray)),
            'The array sent to the create team endpoint is the same one we defined.'
        );

        $headers = $container[0]['request']->getHeaders();

        $this->assertEquals(
            'application/json',
            $headers['Content-Type'][0]
        );
    }
}
