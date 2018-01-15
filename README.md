JustGivingAPI
=============

This is a PHP wrapper around the JustGiving REST API which can be found here: https://developer.justgiving.com/apidocs/documentation

Documentation for this library can be found in the Docs directory: [Documentation](Docs/index.html)

The API is a simple wrapper that uses Guzzle for transport and breaks the API into sections based on the endpoint. So for instance
to get the pages for an event:

```
$api = new JustGivingApi('API_KEY');

$events = $api->getEventsService();

$pages = $events->getPagesForEvent(246);
```

The best way to explore how to use the API is to look at the tests.

## Structure of the API wrapper

The main class is the JustGivingApi class which you instantiate with the API key that you get from the JustGiving developer site. Once you have an instance of that you can then ask it for one of the endpoint services which will then give you a simple interface to one of the JustGiving endpoints.

It may seem like a bit of overkill to have lots of classes rather than one class, but to cover every endpoint in one class would mean that you had one class with a hundred or so functions.

In practice you can chain the calls like this:

```
$pages = $api->getEventsService()->getPagesForEvent(246);
```

