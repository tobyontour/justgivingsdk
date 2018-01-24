JustGivingAPI
=============

This is a PHP wrapper around the JustGiving REST API which can be found here: https://developer.justgiving.com/apidocs/documentation

Documentation for this library can be found in the Docs directory: [Documentation](Docs/README.md)

The API is a simple wrapper that uses Guzzle for transport and breaks the API into sections based on the endpoint. So for instance
to get the pages for an event:

```
$api = new JustGivingApi('API_KEY');

$events = $api->getEventsService();

$pages = $events->getPagesForEvent(246);
```

The best way to explore how to use the API is to look at the tests.

## The structure

A user of the SDK will mostly deal with the JustGivingApi class - requesting services from it (each one representing one of the dozen or so endpoints) and then calling methods on those services to send and receive data often in the form of instances of model classes.
So, for instance, to get a list of the event types that are available you could use this code (with API_KEY replaced with an actual API key):

```
$api = new JustGivingApi('API_KEY');
$eventService = $api->getEventsService();
$pages = $events->getTypes();
```

That will return a list of event types in the form of an array of objects.

```
{
  "description": "",
  "eventType": "PersonalTreks",
  "id": 22,
  "name": "Personal treks"
}
```

In practice you can chain the calls like this:

```
$pages = $api->getEventsService()->getPagesForEvent(246);
```

## A bit more detail

Breaking that down a bit: we first get an instance of the JustGivingApi class, passing it the API_KEY and optionally an OAuth secret if we were using an endpoint that was specific to a user (getting event types would be the same regardless of who you were).

```
$api = new JustGivingApi('API_KEY');
```

So now we have an instance from which we can get the service for dealing with Events. EventsService is a child of the Service class.

```
$eventService = $api->getEventsService();
```

The reason for splitting the API into several classes, one for each endpoint, is that otherwise we'd end up with one huge class that would have to change whenever an endpoint was changed by Just Giving. This way it's a lot more manageable and the actual overhead of a tiny bit more typing is negligible.

So now we have an instance of the EventsService and that has several methods for dealing with events. getTypes() returns an array of objects. The parameter types and return types are all documented in the code like this (for another function that takes a parameter):

```
/**
 * Get an Event by its ID.
 *
 * @param string|int $id The numeric ID of the event to retrieve.
 * @return Event The Event object
 */
 public function getEventById($id)
```

### Services

The Service class simply takes in a Transport instance which it uses to perform the REST calls. The Transport class is a thin wrapper around a Guzzle Client class (Guzzle being a PHP HTTP client). The idea is that the Transport class can be set up with timeouts and any network configuration or authentication methods so that the instances of Services don't have to do that themselves.

### Models

The Model class contains methods for loading an array into a class's properties and exporting the properties to an array. This just makes it easier to program with the different structures the REST API needs whilst making it easy for the Services to convert the arrays from REST calls to objects easily.

Each child class is basically just a data structure:

```
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
```

## ApiException

This exception is there to be thrown in the case of an error that comes back from the API with regards to a call. So, for example, if you tried to create a user that was already there or made a call that required authentication but hadn't provided it then you'll get an ApiException. It's a child of the RuntimeException and adds a public property called $body that contains the body of the response if any (the JustGIving API docs show what the content of an error would be - it's specific to call that's been made). It also sets the error code of the exception to the HTTP response code.

## Documentation

Everything has been extensively documented in the code in comments that can be read by phpDocumentor. This means that when you download the library you can go to the Docs directory and open the index.html file in your browser and it will give you HTML documentation for every class and method. The same documentation can be seen in Bitbucket in a one page format.

When you make code changes that either alter the functionality or adds to it, you need to run phpdoc to update the documentation. This can be done simply on the command line via the Makefile with:

```
make docs
```

This will update all the documentation and you should commit that to the repo.

## Developing

The key to developing on the API (as opposed to with it) is the Makefile which is there to help you.

To get started you need to pull in the dependencies needed to run the library (such as Guzzle) and to run the tests and create documentation files (phpunit, phpdoc, and so on):

```
make install
```

To run the tests:

```
make test
```

To recreate the documentation

```
make docs
```
