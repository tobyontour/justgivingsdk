<?php

namespace JustGivingApi\Services;

use JustGivingApi\Exceptions\ApiException;
use JustGivingApi\JustGivingApi;

class OAuth2Service extends Service
{
    protected $apiKey;
    protected $secret;

    public function __construct($client, $apiKey, $secret)
    {
        parent::__construct($client);
        $this->apiKey = $apiKey;
        $this->secret = $secret;
    }

    /**
     * Throws exception is the redirect URL is invalid.
     *
     * @param  string $url Validates that the redirect URL has all the parts we need.
     */
    private function validateRedirectUrl($redirectUrl)
    {
        if (false === filter_var(
            $redirectUrl,
            FILTER_VALIDATE_URL,
            FILTER_FLAG_SCHEME_REQUIRED | FILTER_FLAG_HOST_REQUIRED | FILTER_FLAG_PATH_REQUIRED
        )
        ) {
            throw new \InvalidArgumentException('Redirect URL needs to have a scheme, host, and path.');
        }
    }

    /**
     * Starts the authentication process by providing the URL to redirect the user to.
     *
     * @param  array $scope
     *  Array of scopes. These include openid, profile, fundraise, account, social, crowdfunding.
     *  The first two are manditory (so the function ensures this). The last two are labelled as
     *  'coming soon' in the API docs.
     * @param string $redirectUrl
     *  This is the full URL that the user will be redirected to after authenticating with JustGiving.
     *  It must match the “Home page for your application” property in 3scale app details exactly, as this is used
     *  for authentication. The URL will be called with a URL parameter of 'code' which must be used in the next
     *  call which will be to getAuthenticationToken($code).
     * @param string $guid
     *  This is a one off randomly generated value to prevent the request from getting modified. A GUID is best as it
     *  ensures uniqueness.
     * @param  string $state
     *  You can use state to allow your application to pick up where it left off, before the redirect to The Resource
     *  Server.
     * @return string The URL to redirect your user to.
     */
    public function getLoginFormUrl(array $scope, $redirectUrl, $guid, $state = '')
    {
        $this->validateRedirectUrl($redirectUrl);

        // Ensure the scope contains manditory elements.
        $scope = array_unique(array_merge($scope, ['openid', 'profile']));

        // Build the query parameters.
        $params = array(
            'client_id' => $this->apiKey,
            'response_type' => 'code',
            'scope' => implode(' ', $scope),
            'redirect_uri' => $redirectUrl,
            'nonce' => $guid,
        );

        if ($state != '') {
            $params['state'] = $state;
        }

        return $this->client->getConfig('base_uri') . 'connect/authorize?' . http_build_query($params);
    }

    /**
     * @param  string $code The code from the response to a request to the URL returned from getLoginFromUrl
     * @param  string $redirectUrl Exactly the same URL used in the call to getLoginFromUrl
     * @return string The authentication token.
     */
    public function getAuthenticationToken($code, $redirectUrl)
    {
        $this->validateRedirectUrl($redirectUrl);

        try {
            $data = $this->post('connect/token', [
                'code' => $code,
                'redirect_uri' => $redirectUrl,
                'grant_type' => 'authorization_code'
            ]);
        } catch (ApiException $e) {
            if ($e->getCode() == 404) {
                return false;
            }
            throw $e;
        }

        return $data->access_token;
    }
}
