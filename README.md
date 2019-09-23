# MiniCrm client
PHP library implementing the MiniCRM API https://www.minicrm.hu/help/minicrm-api/

Supported endpoints
------
##### Schema
- [GET: /Schema/Project/:project_id](https://www.minicrm.hu/help/semak-lekerese#Smklekrse)
    ```php
    $client->getProjectSchema(int $projectId)
    ```
    _Gets back the schema of a Project based on the given ID._
- [GET: /Schema/Person](https://www.minicrm.hu/help/semak-lekerese#Smklekrse)
    ```php
    $client->getPersonSchema()
    ```
    _Gets back the schema of Person entities.
- [GET: /Schema/Business](https://www.minicrm.hu/help/semak-lekerese#Smklekrse)
    ```php
    $client->getBusinessSchema()
    ```
    _Gets back the schema of Business entities._
##### Address
- [GET: /Address/:address_id](https://www.minicrm.hu/help/cim-muveletek/#Cmletlts)
    ```php
    $client->getAddress(int $addressId)
    ```
    _Gets back an Address based on the given ID._
- [GET: /AddressList/:contact_id](https://www.minicrm.hu/help/cim-muveletek/#Cmletlts)
    ```php
    $client->getAddresses(AddressRequest $addressRequest, bool $structured = false)
    ```
    _Gets back the Address(es) of a given Person/Business. By providing
    the second parameter 'true' or 'false' you can decide whether you
    want to get the data structured or not. Default value is 'false'._  
    _The field 'contactId' is mandatory in AddressRequest, see the Basic
    usage section, how to provide it._
    
    __Keep in mind__ if you provide a 'contactId' already in use, the
    method will update that particular contact with the given field's
    values. 
- [PUT: /Address](https://www.minicrm.hu/help/cim-muveletek/#Cmadatmdosts)
    ```php
    $client->createAddress(AddressRequest $addressRequest)
    ``` 
    _Creates a new Address in MiniCRM database based on the provided
    field's values_
    _Fields 'contactId' and 'name' are mandatory, see the Basic usage
    section, how to provide it._ 
##### Category
- [GET: /Category](https://www.minicrm.hu/help/semak-lekerese/) 
    ```php
    $client->getCategories(CategoryRequest $categoryRequest, bool $detailed = false)
    ```
    _Gets back the existing Categories (Modules) of MiniCRM. By providing
    the second parameter 'true' or 'false' you can decide whether you
    want to het the data detailed or not. Default value is 'false'._
        
##### Contact
- [GET: /Contact/:contact_id](https://www.minicrm.hu/help/kontakt-muveletek/)  
    ```php
    $client->getPerson(int $contactId)
    ```
    _Gets back a Person based on the given ID_
    ```php
    $client->getBusiness(int $contactId)
    ```
    _Gets back a Business based on the given ID_
- [PUT: /Contact](https://www.minicrm.hu/help/kontakt-muveletek/#Kontaktadatmdosts)
    ```php
    $client->createPerson(PersonRequest $personRequest)
    ```
    _Creates a new Person in MiniCRM database based on the provided
    field's values_
    _Field 'firstName' is mandatory, see the Basic usage
    section, how to provide it._ 
    ```php
    $client->createBusiness(BusinessRequest $businessRequest)
    ```
    _Creates a new Business in MiniCRM database based on the provided
    field's values_
    _Field 'name' is mandatory, see the Basic usage
    section, how to provide it._ 
##### Project
- [GET: /Project/:project_id](https://www.minicrm.hu/help/projekt-adatmodositas/)
    ```php
    $client->getProject(int $projectId)
    ```
    @todo
    ```php
    $client->getProjectsByCategoryId(int $categoryId)
    ```
    @todo
    ```php
    $client->getProjectsByStatusGroup(string $statusGroup)
    ```
    @todo
    ```php
    $client->getProjectsByUserId(int $userId)
    ```
    @todo
- [PUT: /Project](https://www.minicrm.hu/help/projekt-adatmodositas#Projektadatmdosts)
    ```php
    $client->createProject(ProjectRequest $projectRequest)
    ```
    @todo
##### ToDo
- [GET: /ToDo]
    ```php
    $client->getTodo(int $todoId)
    ```
    @todo
- [GET: /ToDoList]
    ```php
    $client->getTodoList(int $projectId)
    ```
    @todo
- [PUT: /ToDo]
    ```php
    $client->createToDo(TodoRequest $todoRequest)
    ```
    @todo
    ```php
    $client->updateTodo(TodoRequest $todoRequest)
    ```
    @todo

## Basic usage
The MiniCRM client uses separate endpoints as clients, so in order to
use them, you need to pass a Guzzle client, and a Psr NullLogger to the
given endpoint's constructor.

You will need:
- your `systemId` provided by the url of MiniCRM when logged in.
- your `apiKey` from MiniCrm
- the `baseUri` to decide whether you are using the client in a test
environment or not
    - Test environment url: http://r3-test.minicrm.hu/
    - Live environment url: http://r3.minicrm.hu/

```php
<?php

use Cheppers\MiniCrm\Endpoints\AddressEndpoint;

require __DIR__ . '/vendor/autoload.php';

$systemId = 'YOUR_SYSTEM_ID';
$apiKey = 'YOUR_API_KEY';
$baseUri = 'YOUR_URL';
$client = new \GuzzleHttp\Client();
$logger = new \Psr\Log\NullLogger();

$credentials = [
    'baseUri' => $baseUri,
    'apiKey' => $apiKey,
    'systemId' => $systemId
];

$address = new AddressEndpoint($client, $logger);
$address->setCredentials($credentials);

// Printing out the Address with the ID: 1487.
print_r($address->getAddress(1487));

// To check available methods on endpoints, check above.

```
