# MiniCrm client
PHP library implementing the MiniCRM API https://www.minicrm.hu/help/minicrm-api/

Supported endpoints
------
##### Schema
- [GET: /Schema/Project/:project_id](https://www.minicrm.hu/help/semak-lekerese#Smklekrse)
    ```
    $client->getProjectSchema(int $projectId)
    ```
    _Gets back the schema of a Project based on the given ID._
- [GET: /Schema/Person](https://www.minicrm.hu/help/semak-lekerese#Smklekrse)
    ```
    $client->getPersonSchema()
    ```
    _Gets back the schema of Person entities.
- [GET: /Schema/Business](https://www.minicrm.hu/help/semak-lekerese#Smklekrse)
    ```
    $client->getBusinessSchema()
    ```
    _Gets back the schema of Business entities._
##### Address
- [GET: /Address/:address_id](https://www.minicrm.hu/help/cim-muveletek/#Cmletlts)
    ```
    $client->getAddress(int $addressId)
    ```
    _Gets back an Address based on the given ID._
- [GET: /AddressList/:contact_id](https://www.minicrm.hu/help/cim-muveletek/#Cmletlts)
    ```
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
    ```
    $client->createAddress(AddressRequest $addressRequest)
    ``` 
    _Creates a new Address in MiniCRM database based on the provided
    field's values_
    _Fields 'contactId' and 'name' are mandatory, see the Basic usage
    section, how to provide it._ 
##### Category
- [GET: /Category](https://www.minicrm.hu/help/semak-lekerese/) 
    ```
    $client->getCategories(CategoryRequest $categoryRequest, bool $detailed = false)
    ```
    _Gets back the existing Categories (Modules) of MiniCRM. By providing
    the second parameter 'true' or 'false' you can decide whether you
    want to het the data detailed or not. Default value is 'false'._
        
##### Contact
- [GET: /Contact/:contact_id](https://www.minicrm.hu/help/kontakt-muveletek/)  
    ```
    $client->getPerson(int $contactId)
    ```
    _Gets back a Person based on the given ID_
    ```
    $client->getBusiness(int $contactId)
    ```
    _Gets back a Business based on the given ID_
- [PUT: /Contact](https://www.minicrm.hu/help/kontakt-muveletek/#Kontaktadatmdosts)
    ```
    $client->createPerson(PersonRequest $personRequest)
    ```
    _Creates a new Person in MiniCRM database based on the provided
    field's values_
    _Field 'firstName' is mandatory, see the Basic usage
    section, how to provide it._ 
    ```
    $client->createBusiness(BusinessRequest $businessRequest)
    ```
    _Creates a new Business in MiniCRM database based on the provided
    field's values_
    _Field 'name' is mandatory, see the Basic usage
    section, how to provide it._ 
##### Project
@todo
##### ToDo
@todo

## Basic usage
