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

    public function testGetTeam()
    {
        $api = new JustGivingApi('API_KEY', null, true);

        $handlerStack = $this->getMockHandlerStack($container, [
            new Response(200, [], file_get_contents(__DIR__ . '/Mockdata/GetTeam.json'))
        ]);

        $api->setHandlerStack($handlerStack);

        $teamService = $api->getTeamService();

        $team = $teamService->getTeam('test');

        $uri = $container[0]['request']->getUri();

        $this->assertEquals(
            JustGivingApi::SANDBOX_BASE_URL . '/API_KEY/v1/team/test',
            (string)$uri
        );

        $this->assertEquals(
            'GET',
            $container[0]['request']->getMethod()
        );

        $this->assertEquals(
            'JustGivingApi\Models\Team',
            get_class($team)
        );

        $this->assertEquals('Test', $team->name);
        $this->assertEquals(611, $team->id);
        $this->assertEquals('£', $team->localCurrencySymbol);
        $this->assertEquals(0, $team->raisedSoFar);
        $this->assertEquals(890, strlen($team->story));
        $this->assertEquals(0, $team->targetType);

        $this->assertEquals(
            "https://images.justgiving.com/image/default-team-logo.jpg",
            $team->teamImages['teamLogo']['url']
        );
        $this->assertEquals(
            "https://images.justgiving.com/image/default-team-image.jpg",
            $team->teamImages['teamPhoto']['url']
        );
        $this->assertEquals(2, count($team->teamMembers));
        $this->assertEquals("M W for H2Only", $team->teamMembers[1]['pageTitle']);
        $this->assertEquals('Test', $team->teamShortName);
        $this->assertEquals(182.3786, $team->teamTarget);
        $this->assertEquals(1, $team->teamType);
    }

    public function testBadUpdateTeam()
    {
        $api = new JustGivingApi('API_KEY', null, true);

        $teamService = $api->getTeamService();

        // Note the lack of a teamShortName
        $teamArray = [
            'name' => 'The A-Team',
            'story' => 'This is the story of how my life got flipped turned upside-down.',
            'teamTarget' => 3500,
            'targetCurrency' => 'GBP',
            'teamImages.TeamLogo.url' => 'http://example.com/1.jpg',
            'teamImages.TeamPhoto.url' => 'http://example.com/2.jpg'
        ];

        $team = new Team($teamArray);

        $this->expectException(\InvalidArgumentException::class);

        $result = $teamService->updateTeam($team);
    }

    public function testUpdateTeam()
    {
        $api = new JustGivingApi('API_KEY', null, true);

        $handlerStack = $this->getMockHandlerStack($container, [
            new Response(200, [], '{
              "Next.rel": "string",
              "Next.uri": "string",
              "Next.type": "string",
              "teamShortName": "a-team",
              "errorMessage": "string"
            }') // Need actual content from a call.
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
            'teamImages' => [
                'teamLogo' => ['url' => 'http://example.com/1.jpg'],
                'teamPhoto' => ['url' => 'http://example.com/2.jpg']
            ]
        ];

        $team = new Team($teamArray);

        $result = $teamService->updateTeam($team);

        $this->assertEquals(
            JustGivingApi::SANDBOX_BASE_URL . '/API_KEY/v1/team/a-team',
            (string)$container[0]['request']->getUri()
        );

        $this->assertEquals(
            'PUT',
            $container[0]['request']->getMethod()
        );

        $body = json_decode($container[0]['request']->getBody(), true);

        $this->assertEquals($teamArray['name'], $body['name']);
        $this->assertEquals($teamArray['teamTarget'], $body['teamTarget']);
        $this->assertEquals($teamArray['story'], $body['story']);

        $this->assertNotNull($body['teamImages']['teamLogo']['url']);
        $this->assertEquals($teamArray['teamImages']['teamLogo']['url'], $body['teamImages']['teamLogo']['url']);
        $this->assertNotNull($body['teamImages']['teamPhoto']['url']);
        $this->assertEquals($teamArray['teamImages']['teamPhoto']['url'], $body['teamImages']['teamPhoto']['url']);

        // Check that the other elements have been stripped.
        $this->assertEquals(5, count($body));

        $this->assertTrue(is_object($result));
        $this->assertEquals('a-team', $result->teamShortName);
    }

    public function testJoinTeam()
    {
        $api = new JustGivingApi('API_KEY', null, true);

        $handlerStack = $this->getMockHandlerStack($container, [
            new Response(200, [], '') // Need actual content from a call.
        ]);

        $api->setHandlerStack($handlerStack);

        $teamService = $api->getTeamService();

        $teamShortName = 'a-team';
        $pageShortName = 'test-page';

        $result = $teamService->joinTeam($teamShortName, $pageShortName);

        $this->assertEquals(
            JustGivingApi::SANDBOX_BASE_URL . '/API_KEY/v1/team/join/a-team',
            (string)$container[0]['request']->getUri()
        );

        $this->assertEquals(
            'PUT',
            $container[0]['request']->getMethod()
        );

        $body = json_decode($container[0]['request']->getBody(), true);

        $this->assertEquals('test-page', $body['pageShortName']);
    }
}
