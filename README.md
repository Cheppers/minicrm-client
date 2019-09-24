# MiniCrm client
PHP library implementing the MiniCRM API https://www.minicrm.hu/help/minicrm-api/

Supported endpoints
------
#### Schema
- [GET: /Schema/Project/:project_id](https://www.minicrm.hu/help/semak-lekerese#Smklekrse)

    _Gets back the schema of a Project based on the given ID._
    ```php
    $client->getProjectSchema(int $projectId);
    ```
- [GET: /Schema/Person](https://www.minicrm.hu/help/semak-lekerese#Smklekrse)

    _Gets back the schema of Person entities._
    ```php
    $client->getPersonSchema();
    ```
- [GET: /Schema/Business](https://www.minicrm.hu/help/semak-lekerese#Smklekrse)

     _Gets back the schema of Business entities._
    ```php
    $client->getBusinessSchema();
    ```
#### Address
- [GET: /Address/:address_id](https://www.minicrm.hu/help/cim-muveletek/#Cmletlts)

    _Gets back an Address based on the given ID._
    ```php
    $client->getAddress(int $addressId);
    ```
- [GET: /AddressList/:contact_id](https://www.minicrm.hu/help/cim-muveletek/#Cmletlts)

    _Gets back the Address(es) of a given Person/Business. By providing
    the second parameter 'true' or 'false' you can decide whether you
    want to get the data structured or not. Default value is 'false'._  
    _The field 'contactId' is mandatory in AddressRequest, see the Basic
    usage section, how to provide it._
    
    __Keep in mind__ if you provide a 'contactId' already in use, the
    method will update that particular contact with the given field's
    values. 
    ```php
    $client->getAddresses(AddressRequest $addressRequest, bool $structured = false);
    ```
- [PUT: /Address](https://www.minicrm.hu/help/cim-muveletek/#Cmadatmdosts)
    
    _Creates a new Address in MiniCRM database based on the provided
    field's values_
    _Fields 'contactId' and 'name' are mandatory, see the Basic usage
    section, how to provide it._ 
    ```php
    $client->createAddress(AddressRequest $addressRequest);
    ``` 
- [PUT: /Address/:address_id](https://www.minicrm.hu/help/cim-muveletek/#Cmadatmdosts)

    _Updates an Address based on the given ID._  
    _See the basic usage section how to provide the ID._
    ```php
    $client->updateAddress(AddressRequest $addressRequest);
    ```
#### Category
- [GET: /Category](https://www.minicrm.hu/help/semak-lekerese/)

    _Gets back the existing Categories (Modules) of MiniCRM. By providing
    the second parameter 'true' or 'false' you can decide whether you
    want to het the data detailed or not. Default value is 'false'._
    ```php
    $client->getCategories(CategoryRequest $categoryRequest, bool $detailed = false);
    ```
        
#### Contact
- [GET: /Contact/:contact_id](https://www.minicrm.hu/help/kontakt-muveletek/)  
    
    _Gets back a Person based on the given ID_
    ```php
    $client->getPerson(int $contactId);
    ```

    _Gets back a Business based on the given ID_
    ```php
    $client->getBusiness(int $contactId);
    ```
- [PUT: /Contact](https://www.minicrm.hu/help/kontakt-muveletek/#Kontaktadatmdosts)
    ```php
    $client->createPerson(PersonRequest $personRequest);
    ```
    _Creates a new Person in MiniCRM database based on the provided
    field's values_
    _Field 'firstName' is mandatory, see the Basic usage
    section, how to provide it._ 
    ```php
    $client->createBusiness(BusinessRequest $businessRequest);
    ```
    _Creates a new Business in MiniCRM database based on the provided
    field's values._
    _Field 'name' is mandatory, see the Basic usage
    section, how to provide it._ 
- [PUT: /Contact/:contact_id](https://www.minicrm.hu/help/kontakt-muveletek/#Kontaktadatmdosts)
    
    _Updates a Person based on the given ID._  
    _See the basic usage section how to provide the ID._
    ```php
    $contact->updatePerson(PersonRequest $personRequest);
    ```
    _Updates a Business based on the given ID._  
    _See the basic usage section how to provide the ID._
    ```php
    $contact->updateBusiness(BusinessRequest $businessRequest);
    ```
#### Project
- [GET: /Project/:project_id](https://www.minicrm.hu/help/projekt-adatmodositas/)

    _Gets back a Project based on the given ID._
    ```php
    $client->getProject(int $projectId);
    ```
- [GET: /Project?CategoryId=:category_id](https://www.minicrm.hu/help/projekt-adatmodositas/)

    _Gets back a Project based on the given CategoryID._
    ```php
    $client->getProjectsByCategoryId(int $categoryId);
    ```
- [GET: /Project?StatusGroup=:status_group](https://www.minicrm.hu/help/projekt-adatmodositas/)

    _Gets back a Project based on the Status Group._  
    _Status group values can be: 'Lead', 'Open', 'Success', 'Failed'._
    ```php
    $client->getProjectsByStatusGroup(string $statusGroup);
    ```
- [GET: /Project?UserId=:user_id](https://www.minicrm.hu/help/projekt-adatmodositas/)

    _Gets back Project based on the given UserID._
    ```php
    $client->getProjectsByUserId(int $userId);
    ```
- [GET: /EmailList/:project_id](https://www.minicrm.hu/help/projekt-adatmodositas/)

    _Gets back a given Project's all emails based on the provided ID._  
    _See the basic usage section how to provide the ID._
    ```php
    $client->getProjectEmails(ProjectRequest $projectRequest);
    ```

- [PUT: /Project](https://www.minicrm.hu/help/projekt-adatmodositas#Projektadatmdosts)

    _Creates a new Project in MiniCRM database based on the provided
    field's values._
    _Fields 'categoryId' and 'contactId' are mandatory, see the Basic
    usage section, how to provide it._
    ```php
    $client->createProject(ProjectRequest $projectRequest);
    ```
- [PUT: /Project/:project_id](https://www.minicrm.hu/help/projekt-adatmodositas#Projektadatmdosts)
    
    _Updates a Project based on the given ID._
    _See the basic usage section how to provide the ID._
    ```php
    $client->updateProject(ProjectRequest $projectRequest);
    ```
#### ToDo
- [GET: /ToDo](https://www.minicrm.hu/help/teendo-muveletek/#Teendletlts)

    _Gets back a ToDo based on the given ID._
    ```php
    $client->getTodo(int $todoId);
    ```
- [GET: /ToDoList](https://www.minicrm.hu/help/teendo-muveletek/)

    _Gets back a Todos of a given Project based on the given ID._
    ```php
    $client->getTodoList(int $projectId);
    ```
- [PUT: /ToDo](https://www.minicrm.hu/help/teendo-muveletek/)

    _Creates a new ToDo in MiniCRM database based on the provided
    field's values._  
    _Field 'projectId' is mandatory, see the Basic usage section, how
    to provide it._
    ```php
    $client->createToDo(TodoRequest $todoRequest);
    ```

- [PUT: /ToDo/:todo_id](https://www.minicrm.hu/help/teendo-muveletek/)
    
    _Updates a ToDo based on the given ID._
    _See the basic usage section how to provide the ID._
    ```php
    $client->updateTodo(TodoRequest $todoRequest);
    ```
#### Template
- [GET: /Template/:template_id](https://www.minicrm.hu/help/sablon-muveletek/)

    _Gets back a Template based on the given ID._
    ```php
    $client->getTemplate(int $templateId);
    ```
- [GET: /TemplateList/:category_id](https://www.minicrm.hu/help/sablon-muveletek/)

    _Gets back a list of Templates according to a Category based on the
    given ID._  
    _Field 'categoryId' is mandatory, see the Basic usage section, how 
    to provide it._
    ```php
    $client->getTemplateList(TemplateRequest $templateRequest);
    ```

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

use Cheppers\MiniCrm\DataTypes\Address\AddressRequest;
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

// Example for creating an Address.
print_r($address->createAddress(AddressRequest::__set_state([
    'contactId' => 1,
    'type' => 'Test Type', // Check the given types in MiniCRM. Use getSchema().
    'name' => 'Test Address',
    'countryId' => 'Test Country',
    'postalCode' => 1,
    'city' => 'Test City',
    'county' => 'Test County',
    'address' => 'Test Address',
    'default' => 0
])));

// Providing fields when updating an address.
print_r($address->updateAddress(AddressRequest::__set_state([
    'id' => 1,
    'name' => 'updated-name',
])));

```
 To check available methods on endpoints, check above.


