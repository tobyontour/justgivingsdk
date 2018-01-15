<?php

/**
 * The OAuth2Service class.
 */

namespace JustGivingApi\Services;

use JustGivingApi\Exceptions\ApiException;
use JustGivingApi\JustGivingApi;

/**
 * The OAuth2Service class.
 */
class OAuth2Service extends Service
{
    /**
     * The app's Api key
     * @var string
     */
    protected $apiKey;

    /**
     * The OAuth2 Secret for the app.
     * @var string
     */
    protected $secret;

    /**
     * Constructor.
     *
     * @param \JustGivingApi\Transport\transport $transport The transport that makes requests.
     * @param string $apiKey The API key for the app.
     * @param string $secret The OAuth2 secret for the app.
     */
    public function __construct($transport, $apiKey, $secret)
    {
        parent::__construct($transport);
        $this->apiKey = $apiKey;
        $this->secret = $secret;
    }

    /**
     * Returns false if the redirect URL is invalid.
     *
     * @param  string $redirectUrl Validates that the redirect URL has all the parts we need.
     * @return  boolean True if it's valid.
     */
    private function validateRedirectUrl($redirectUrl)
    {
        return false !== filter_var(
            $redirectUrl,
            FILTER_VALIDATE_URL,
            FILTER_FLAG_SCHEME_REQUIRED | FILTER_FLAG_HOST_REQUIRED | FILTER_FLAG_PATH_REQUIRED
        );
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
        if (!$this->validateRedirectUrl($redirectUrl)) {
            throw new \InvalidArgumentException('Redirect URL needs to have a scheme, host, and path.');
        }


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

        return $this->transport->getBaseUrl() . 'connect/authorize?' . http_build_query($params);
    }

    /**
     * Gets the authentication token needed to nake requests on behalf of the user.
     *
     * @param  string $code The code from the response to a request to the URL returned from getLoginFromUrl
     * @param  string $redirectUrl Exactly the same URL used in the call to getLoginFromUrl
     * @return object An object with properties of access_token and, optionally, refresh_token.
     *  The refresh token will only be present if the original authentication request asked for the offline_access
     *  scope.
     */
    public function getAuthenticationToken($code, $redirectUrl)
    {
        if (!$this->validateRedirectUrl($redirectUrl)) {
            throw new \InvalidArgumentException('Redirect URL needs to have a scheme, host, and path.');
        }

        try {
            $this->transport->setBasicAuth($this->apiKey, $this->secret);
            $data = $this->transport->post('connect/token', [
                'code' => $code,
                'redirect_uri' => $redirectUrl,
                'grant_type' => 'authorization_code'
            ], false, true);
        } catch (ApiException $e) {
            if ($e->getCode() == 404) {
                return false;
            }
            throw $e;
        }

        return $data;
    }

    /**
     * Refreshes an expired access token and returns a new access token and refresh token.
     *
     * @param string The refresh token.
     * @param string $redirectUrl Exactly the same URL used in the call to getLoginFromUrl
     * @return object An object with properties of access_token and, optionally, refresh_token.
     */
    public function refreshAuthenticationToken($refreshToken, $redirectUrl)
    {
        if (!$this->validateRedirectUrl($redirectUrl)) {
            var_dump($redirectUrl);
            throw new \InvalidArgumentException('Redirect URL needs to have a scheme, host, and path.');
        }

        try {
            $this->transport->setBasicAuth($this->apiKey, $this->secret);
            $data = $this->transport->post('connect/token', [
                'refresh_token' => $refreshToken,
                'redirect_uri' => $redirectUrl,
                'grant_type' => 'refresh_token'
            ], false, true);
        } catch (ApiException $e) {
            if ($e->getCode() == 404) {
                return false;
            }
            throw $e;
        }

        return $data;
    }
}
