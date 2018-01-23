# JustGivingSdk Documentation

## Table of Contents

* [Account](#account)
    * [__construct](#__construct)
    * [toArray](#toarray)
* [AccountsService](#accountsservice)
    * [__construct](#__construct-1)
    * [accountExists](#accountexists)
    * [accountCreate](#accountcreate)
* [ApiException](#apiexception)
    * [__construct](#__construct-2)
* [Event](#event)
    * [__construct](#__construct-3)
    * [toArray](#toarray-1)
* [EventsService](#eventsservice)
    * [__construct](#__construct-4)
    * [getTypes](#gettypes)
    * [getEventById](#geteventbyid)
    * [getPagesForEvent](#getpagesforevent)
    * [createEvent](#createevent)
* [FundraisingPage](#fundraisingpage)
    * [__construct](#__construct-5)
    * [toArray](#toarray-2)
* [FundraisingService](#fundraisingservice)
    * [__construct](#__construct-6)
    * [pageCreate](#pagecreate)
* [JustGivingApi](#justgivingapi)
    * [__construct](#__construct-7)
    * [setHandlerStack](#sethandlerstack)
    * [getTransport](#gettransport)
    * [setAccessToken](#setaccesstoken)
    * [getEventsService](#geteventsservice)
    * [getAccountsService](#getaccountsservice)
    * [getFundraisingService](#getfundraisingservice)
    * [getTeamService](#getteamservice)
    * [getLoginFormUrl](#getloginformurl)
    * [getAuthenticationToken](#getauthenticationtoken)
    * [refreshAuthenticationToken](#refreshauthenticationtoken)
* [Model](#model)
    * [__construct](#__construct-8)
    * [toArray](#toarray-3)
* [OAuth2Service](#oauth2service)
    * [__construct](#__construct-9)
    * [getLoginFormUrl](#getloginformurl-1)
    * [getAuthenticationToken](#getauthenticationtoken-1)
    * [refreshAuthenticationToken](#refreshauthenticationtoken-1)
* [Service](#service)
    * [__construct](#__construct-10)
* [Team](#team)
    * [__construct](#__construct-11)
    * [toArray](#toarray-4)
* [TeamService](#teamservice)
    * [__construct](#__construct-12)
    * [createTeam](#createteam)
* [Transport](#transport)
    * [__construct](#__construct-13)
    * [getBaseUrl](#getbaseurl)
    * [setBasicAuth](#setbasicauth)
    * [disableBasicAuth](#disablebasicauth)
    * [get](#get)
    * [post](#post)
    * [put](#put)

## Account

Class representing a JustGiving account



* Full name: \JustGivingApi\Models\Account
* Parent class: \JustGivingApi\Models\Model


### __construct

Constructor.

```php
Account::__construct( array $data = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$data` | **array** |  |




---

### toArray

Convert the object to an array.

```php
Account::toArray(  ): array
```





**Return Value:**

The array to send as part of a REST request.



---

## AccountsService

Service that wraps up JustGiving API endpoints starting with /account.



* Full name: \JustGivingApi\Services\AccountsService
* Parent class: \JustGivingApi\Services\Service


### __construct

Constructor.

```php
AccountsService::__construct( \JustGivingApi\Transport\Transport $transport )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$transport` | **\JustGivingApi\Transport\Transport** | The transport that performs HTTP requests. |




---

### accountExists

Finds out if an account already exists for a given email address.

```php
AccountsService::accountExists( string $emailAddress ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$emailAddress` | **string** | The email address to search for an account for. |


**Return Value:**

True if an account with that email address exists.



---

### accountCreate

Create a user account.

```php
AccountsService::accountCreate( \JustGivingApi\Models\Account $account ): Object
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$account` | **\JustGivingApi\Models\Account** | The account object to create. |


**Return Value:**

containing the response data:
{
  "email": "string",
  "country": "string",
  "Error.id": "string",
  "Error.desc": "string",
  "errorMessage": "string"
}



---

## ApiException

Exception for API calls that nicely formats them and sets the status code.



* Full name: \JustGivingApi\Exceptions\ApiException
* Parent class: 


### __construct

Constructor.

```php
ApiException::__construct( \GuzzleHttp\Psr7\Response $response, string $url )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$response` | **\GuzzleHttp\Psr7\Response** | The response from the Guzzle call. |
| `$url` | **string** | The URL that was called. |




---

## Event

Event class that encapsulates what makes an event in JustGiving.



* Full name: \JustGivingApi\Models\Event
* Parent class: \JustGivingApi\Models\Model


### __construct

Constructor.

```php
Event::__construct( array $data = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$data` | **array** |  |




---

### toArray

Convert the object to an array.

```php
Event::toArray(  ): array
```





**Return Value:**

The array to send as part of a REST request.



---

## EventsService

Deals wioth calls to the /event endpoint and child endpoints.



* Full name: \JustGivingApi\Services\EventsService
* Parent class: \JustGivingApi\Services\Service


### __construct

Constructor.

```php
EventsService::__construct( \JustGivingApi\Transport\Transport $transport )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$transport` | **\JustGivingApi\Transport\Transport** | The transport that performs HTTP requests. |




---

### getTypes

Get the types of event that Just Giving recognise.

```php
EventsService::getTypes(  ): array
```





**Return Value:**

List of types of events in the form of and array of objects.
 {
   ["description"]=> string(0) ""
   ["eventType"]=> string(13) "PersonalTreks"
   ["id"]=> int(22)
   ["name"]=> string(14) "Personal treks"
}



---

### getEventById

Get an Event by its ID.

```php
EventsService::getEventById( string|integer $id ): \JustGivingApi\Models\Event
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$id` | **string&#124;integer** | The numeric ID of the event to retrieve. |


**Return Value:**

The Event object



---

### getPagesForEvent

Get the donation pages associated with an Event.

```php
EventsService::getPagesForEvent( string|integer $id, integer $pageNumber = null, integer $pageSize = null ): \JustGivingApi\Services\The
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$id` | **string&#124;integer** | The numeric ID of the event to get pages for. |
| `$pageNumber` | **integer** | The page number to retrieve. |
| `$pageSize` | **integer** | The size of each retrieved page. |


**Return Value:**

array of event pages.



---

### createEvent

Create an event.

```php
EventsService::createEvent( \JustGivingApi\Models\Event $event ): object
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$event` | **\JustGivingApi\Models\Event** |  |


**Return Value:**

Contains



---

## FundraisingPage

Class that encapsulates a fundraising page in JustGiving.



* Full name: \JustGivingApi\Models\FundraisingPage
* Parent class: \JustGivingApi\Models\Model


### __construct

Constructor.

```php
FundraisingPage::__construct( array $data = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$data` | **array** |  |




---

### toArray

Convert the object to an array.

```php
FundraisingPage::toArray(  ): array
```





**Return Value:**

The array to send as part of a REST request.



---

## FundraisingService

Service that wraps up JustGiving API endpoints starting with /fundraising.



* Full name: \JustGivingApi\Services\FundraisingService
* Parent class: \JustGivingApi\Services\Service


### __construct

Constructor.

```php
FundraisingService::__construct( \JustGivingApi\Transport\Transport $transport )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$transport` | **\JustGivingApi\Transport\Transport** | The transport that performs HTTP requests. |




---

### pageCreate



```php
FundraisingService::pageCreate( \JustGivingApi\Models\FundraisingPage $page ): Object
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$page` | **\JustGivingApi\Models\FundraisingPage** | The page data to be used to create the page |


**Return Value:**

containing the response data:
 {
     "Next.rel": "string",
     "Next.uri": "string",
     "Next.type": "string",
     "Error.id": "string",
     "Error.desc": "string",
     "pageId": 0,
     "signOnUrl": "string",
     "errorMessage": "string"
 }



---

## JustGivingApi

Main class for making requests to the JustGiving API.



* Full name: \JustGivingApi\JustGivingApi


### __construct

Creates an instance of the API.

```php
JustGivingApi::__construct( string $apiKey, string $secret = null, boolean $testMode = false, integer $version = 1 )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$apiKey` | **string** | The JustGiving API key. Register as a developer on the website to get this. |
| `$secret` | **string** | The API secret. Needed for authenticated requests on behalf of the user. |
| `$testMode` | **boolean** | If true it uses the sandbox environment. Defaults to false (production). |
| `$version` | **integer** | API version. |




---

### setHandlerStack

Allows overriding of the transport mechanism.

```php
JustGivingApi::setHandlerStack(  $stack )
```

This must be called before getting any services.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$stack` | **** |  |




---

### getTransport

Sets up the transport for performing API calls.

```php
JustGivingApi::getTransport(  ): \JustGivingApi\JustGivingApi\Transport\Transport
```





**Return Value:**

The client for making REST calls.



---

### setAccessToken

Sets the access token to use to authenticate as a user.

```php
JustGivingApi::setAccessToken( string $accessToken )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$accessToken` | **string** | The access token retrieved via getAuthenticationToken(). |




---

### getEventsService

The API is split into services, one for each endpoint to allow for building each
endpoint in its entirety and to make sure that we don't end up with one class with
1001 methods in it.

```php
JustGivingApi::getEventsService(  ): \JustGivingApi\JustGivingApi\Services\EventsService
```





**Return Value:**

The events service.



---

### getAccountsService

Gets and instance of the AccountsService.

```php
JustGivingApi::getAccountsService(  ): \JustGivingApi\JustGivingApi\Services\AccountsService
```





**Return Value:**

The accounts service.



---

### getFundraisingService

Gets the fundraising service.

```php
JustGivingApi::getFundraisingService(  ): \JustGivingApi\JustGivingApi\Services\FundraisingService
```





**Return Value:**

The fundraising service.



---

### getTeamService

Gets the team service.

```php
JustGivingApi::getTeamService(  ): \JustGivingApi\JustGivingApi\Services\TeamService
```





**Return Value:**

The team service.



---

### getLoginFormUrl

Starts the authentication process by providing the URL to redirect the user to.

```php
JustGivingApi::getLoginFormUrl( array $scope, string $redirectUrl, string $guid, string $state = &#039;&#039; ): string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$scope` | **array** | Array of scopes. These include openid, profile, fundraise, account, social, crowdfunding, and offline_access.
 The first two are manditory (so the function ensures this). The last two are labelled as
 'coming soon' in the API docs.
 You *must* include the scope 'offline_access' if you want to be able to use the token beyond the normal timeout
 which is about an hour. |
| `$redirectUrl` | **string** | This is the full URL that the user will be redirected to after authenticating with JustGiving.
 It must match the “Home page for your application” property in 3scale app details exactly, as this is used
 for authentication. The URL will be called with a URL parameter of 'code' which must be used in the next
 call which will be to getAuthenticationToken($code). |
| `$guid` | **string** | This is a one off randomly generated value to prevent the request from getting modified. A GUID is best as it
 ensures uniqueness. |
| `$state` | **string** | You can use state to allow your application to pick up where it left off, before the redirect to The Resource
 Server. |


**Return Value:**

The URL to redirect your user to.



---

### getAuthenticationToken

Retrieves an authentication token to act on bhalf of a user.

```php
JustGivingApi::getAuthenticationToken( string $code, string $redirectUrl ): object
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$code` | **string** | The code returned as a parameter to the redirect URL. See getLoginFormUrl(). |
| `$redirectUrl` | **string** | The redirect URL. See docs for getLoginFormUrl(). |


**Return Value:**

Object containing access_token and (optionally) refresh_token



---

### refreshAuthenticationToken

Refreshes the authentication token if it has expired.

```php
JustGivingApi::refreshAuthenticationToken( string $refreshToken, string $redirectUrl ): object
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$refreshToken` | **string** | The refresh token. This will have come from the getAuthenticationToken() call. |
| `$redirectUrl` | **string** | The redirect URL. See docs for getLoginFormUrl(). |


**Return Value:**

Object containing access_token and refresh_token



---

## Model





* Full name: \JustGivingApi\Models\Model


### __construct

Constructor.

```php
Model::__construct( array $data = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$data` | **array** |  |




---

### toArray

Convert the object to an array.

```php
Model::toArray(  ): array
```





**Return Value:**

The array to send as part of a REST request.



---

## OAuth2Service

The OAuth2Service class.



* Full name: \JustGivingApi\Services\OAuth2Service
* Parent class: \JustGivingApi\Services\Service


### __construct

Constructor.

```php
OAuth2Service::__construct( \JustGivingApi\Transport\transport $transport, string $apiKey, string $secret )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$transport` | **\JustGivingApi\Transport\transport** | The transport that makes requests. |
| `$apiKey` | **string** | The API key for the app. |
| `$secret` | **string** | The OAuth2 secret for the app. |




---

### getLoginFormUrl

Starts the authentication process by providing the URL to redirect the user to.

```php
OAuth2Service::getLoginFormUrl( array $scope, string $redirectUrl, string $guid, string $state = &#039;&#039; ): string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$scope` | **array** | Array of scopes. These include openid, profile, fundraise, account, social, crowdfunding.
 The first two are manditory (so the function ensures this). The last two are labelled as
 'coming soon' in the API docs. |
| `$redirectUrl` | **string** | This is the full URL that the user will be redirected to after authenticating with JustGiving.
 It must match the “Home page for your application” property in 3scale app details exactly, as this is used
 for authentication. The URL will be called with a URL parameter of 'code' which must be used in the next
 call which will be to getAuthenticationToken($code). |
| `$guid` | **string** | This is a one off randomly generated value to prevent the request from getting modified. A GUID is best as it
 ensures uniqueness. |
| `$state` | **string** | You can use state to allow your application to pick up where it left off, before the redirect to The Resource
 Server. |


**Return Value:**

The URL to redirect your user to.



---

### getAuthenticationToken

Gets the authentication token needed to nake requests on behalf of the user.

```php
OAuth2Service::getAuthenticationToken( string $code, string $redirectUrl ): object
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$code` | **string** | The code from the response to a request to the URL returned from getLoginFromUrl |
| `$redirectUrl` | **string** | Exactly the same URL used in the call to getLoginFromUrl |


**Return Value:**

An object with properties of access_token and, optionally, refresh_token.
 The refresh token will only be present if the original authentication request asked for the offline_access
 scope.



---

### refreshAuthenticationToken

Refreshes an expired access token and returns a new access token and refresh token.

```php
OAuth2Service::refreshAuthenticationToken(  $refreshToken, string $redirectUrl ): object
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$refreshToken` | **** |  |
| `$redirectUrl` | **string** | Exactly the same URL used in the call to getLoginFromUrl |


**Return Value:**

An object with properties of access_token and, optionally, refresh_token.



---

## Service

Base class for services.



* Full name: \JustGivingApi\Services\Service


### __construct

Constructor.

```php
Service::__construct( \JustGivingApi\Transport\Transport $transport )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$transport` | **\JustGivingApi\Transport\Transport** | The transport that performs HTTP requests. |




---

## Team

Class that encapsulates what makes a team in JustGiving.



* Full name: \JustGivingApi\Models\Team
* Parent class: \JustGivingApi\Models\Model


### __construct

Constructor.

```php
Team::__construct( array $data = array() )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$data` | **array** |  |




---

### toArray

Convert the object to an array.

```php
Team::toArray(  ): array
```





**Return Value:**

The array to send as part of a REST request.



---

## TeamService

Deals wioth calls to the /team endpoint and child endpoints.



* Full name: \JustGivingApi\Services\TeamService
* Parent class: \JustGivingApi\Services\Service


### __construct

Constructor.

```php
TeamService::__construct( \JustGivingApi\Transport\Transport $transport )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$transport` | **\JustGivingApi\Transport\Transport** | The transport that performs HTTP requests. |




---

### createTeam

Create a team.

```php
TeamService::createTeam( \JustGivingApi\Models\Team $team ): object
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$team` | **\JustGivingApi\Models\Team** |  |


**Return Value:**

Contains



---

## Transport

A class allowing for REST requests.



* Full name: \JustGivingApi\Transport\Transport


### __construct

Constructor.

```php
Transport::__construct( \GuzzleHttp\Client $client )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$client` | **\GuzzleHttp\Client** |  |




---

### getBaseUrl

Returns the base url of requests.

```php
Transport::getBaseUrl(  ): string
```





**Return Value:**

The base URL for requests.



---

### setBasicAuth

Sets the credentials for Basic Auth and turns it on.

```php
Transport::setBasicAuth( string $username, string $password )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$username` | **string** | The username. |
| `$password` | **string** | The password. |




---

### disableBasicAuth

Disable basic auth.

```php
Transport::disableBasicAuth(  )
```







---

### get

HTTP GET request.

```php
Transport::get( string $path, boolean|boolean $assoc = false ): mixed
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$path` | **string** | The path to get from. |
| `$assoc` | **boolean&#124;boolean** | Whether to return an associative array or not. |


**Return Value:**

object or array depending on the value of $assoc



---

### post

HTTP POST request.

```php
Transport::post( string $path, array $body = array(), boolean $assoc = false, boolean $sendAsFormEncoded = false ): mixed
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$path` | **string** | The path to send the request to. |
| `$body` | **array** | The body of the POST. It will be encoded into JSON for you. |
| `$assoc` | **boolean** | If true will return an associative array, the default is false to return an object. |
| `$sendAsFormEncoded` | **boolean** | If true the $body array will be sent as form elements instead. |


**Return Value:**

object or array depending on the value of $assoc



---

### put

HTTP PUT request.

```php
Transport::put( string $path,  $body = array(),  $assoc = false ): mixed
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$path` | **string** | The path to send the request to. |
| `$body` | **** |  |
| `$assoc` | **** |  |


**Return Value:**

object or array depending on the value of $assoc



---



--------
> This document was automatically generated from source code comments on 2018-01-23 using [phpDocumentor](http://www.phpdoc.org/) and [cvuorinen/phpdoc-markdown-public](https://github.com/cvuorinen/phpdoc-markdown-public)
