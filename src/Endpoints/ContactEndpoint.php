<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Endpoints;

use Cheppers\MiniCrm\DataTypes\Contact\BusinessRequest;
use Cheppers\MiniCrm\DataTypes\Contact\BusinessResponse;
use Cheppers\MiniCrm\DataTypes\Contact\ContactRequestBase;
use Cheppers\MiniCrm\DataTypes\Contact\PersonRequest;
use Cheppers\MiniCrm\DataTypes\Contact\PersonResponse;
use Cheppers\MiniCrm\MiniCrmClient;

class ContactEndpoint extends MiniCrmClient
{

    /**
     * Gets back a Person based on it's contactId.
     *
     * @param int $contactId
     *
     * @return \Cheppers\MiniCrm\DataTypes\Contact\PersonResponse
     *
     * @throws \Exception
     */
    public function getPerson(int $contactId): PersonResponse
    {
        $body = $this->getContact($contactId);
        $contact = PersonResponse::__set_state($body);

        return $contact;
    }

    /**
     * Gets back a Business based on it's contactId.
     *
     * @param int $contactId
     *
     * @return \Cheppers\MiniCrm\DataTypes\Contact\BusinessResponse
     *
     * @throws \Exception
     */
    public function getBusiness(int $contactId): BusinessResponse
    {
        $body = $this->getContact($contactId);
        $contact = BusinessResponse::__set_state($body);

        return $contact;
    }

    /**
     * @param \Cheppers\MiniCrm\DataTypes\Contact\PersonRequest $personRequest
     *
     * @return array
     *
     * @throws \Exception
     */
    public function createPerson(PersonRequest $personRequest): array
    {
        $response = $this->sendRequest('PUT', $personRequest, '/Contact');
        $body = $this->validateAndParseResponse($response);

        return $body;
    }

    /**
     * @param \Cheppers\MiniCrm\DataTypes\Contact\BusinessRequest $businessRequest
     *
     * @return array
     *
     * @throws \Exception
     */
    public function createBusiness(BusinessRequest $businessRequest): array
    {
        $response = $this->sendRequest('PUT', $businessRequest, '/Contact');
        $body = $this->validateAndParseResponse($response);

        return $body;
    }

    /**
     * Deletes a Contact.
     *
     * @param int $contactId
     *
     * @return array
     *
     * @throws \Exception
     *  Throws 'Invalid response' exception when trying to delete a Contact with
     *  a non-existing ContactId, or a Contact that is marked as primary on any
     *  Project.
     */
    public function deleteContact(int $contactId): array
    {
        $response = $this->sendRequest(
            'GET',
            ContactRequestBase::__set_state([]),
            "/PurgePerson/{$contactId}"
        );

        $body = $this->validateAndParseResponse($response);

        return $body;
    }

    /**
     * @param int $contactId
     *
     * @return array
     *
     * @throws \Exception
     */
    protected function getContact(int $contactId): array
    {
        $response = $this->sendRequest(
            'GET',
            ContactRequestBase::__set_state([]),
            "/Contact/{$contactId}"
        );

        return $this->validateAndParseResponse($response);
    }
}
