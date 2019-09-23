# MiniCrm client
PHP library implementing the MiniCRM API https://minicrm.hu

Supported endpoints
------
- Address
    - [GET: /Address/:address_id](https://www.minicrm.hu/help/cim-muveletek/#Cmletlts) `$client->getAddress(int $addressId)`  
    Gets back a given Address based on its ID.
    - [GET: /AddressList/:contact_id](https://www.minicrm.hu/help/cim-muveletek/#Cmletlts) `$client->getAddresses(['ContactId' => $contactId])`  
    Gets back the Address(es) of a given Person/Business. By providing the second parameter
    'true' or 'false' you can decide whether you want to get the data structured or not.
    - [PUT: /Address](https://www.minicrm.hu/help/cim-muveletek/#Cmadatmdosts) `$client->createAddress(['ContactId' => $contactId])`  


- Category

- Contact
- Project
- ToDo
- Order

## Basic usage
