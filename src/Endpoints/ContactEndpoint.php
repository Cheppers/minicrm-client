<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Endpoints;

use Cheppers\MiniCrm\DataTypes\Contact\Business\BusinessRequest;
use Cheppers\MiniCrm\DataTypes\Contact\Business\BusinessResponse;
use Cheppers\MiniCrm\DataTypes\Contact\ContactRequestBase;
use Cheppers\MiniCrm\DataTypes\Contact\Person\PersonRequest;
use Cheppers\MiniCrm\DataTypes\Contact\Person\PersonResponse;
use Cheppers\MiniCrm\MiniCrmClient;

class ContactEndpoint extends MiniCrmClient
{

    /**
     * Gets back a Person based on it's contactId.
     *
     * @param int $contactId
     *
     * @return \Cheppers\MiniCrm\DataTypes\Contact\Person\PersonResponse
     *
     * @throws \Exception
     */
    public function getPerson(int $contactId): PersonResponse
    {
        $body = $this->getContact($contactId);

        return PersonResponse::__set_state($body);
    }

    /**
     * Gets back a Business based on it's contactId.
     *
     * @param int $contactId
     *
     * @return \Cheppers\MiniCrm\DataTypes\Contact\Business\BusinessResponse
     *
     * @throws \Exception
     */
    public function getBusiness(int $contactId): BusinessResponse
    {
        $body = $this->getContact($contactId);

        return BusinessResponse::__set_state($body);
    }

    /**
     * @param \Cheppers\MiniCrm\DataTypes\Contact\Person\PersonRequest $personRequest
     *
     * @return array
     *
     * @throws \Exception
     */
    public function createPerson(PersonRequest $personRequest): array
    {
        $response = $this->sendRequest('PUT', $personRequest, '/Contact');

        return $this->validateAndParseResponse($response);
    }

    /**
     * @param \Cheppers\MiniCrm\DataTypes\Contact\Business\BusinessRequest $businessRequest
     *
     * @return array
     *
     * @throws \Exception
     */
    public function createBusiness(BusinessRequest $businessRequest): array
    {
        $response = $this->sendRequest('PUT', $businessRequest, '/Contact');

        return $this->validateAndParseResponse($response);
    }

    /**
     * @param \Cheppers\MiniCrm\DataTypes\Contact\Person\PersonRequest $personRequest
     *
     * @return array
     *
     * @throws \Exception
     */
    public function updatePerson(PersonRequest $personRequest): array
    {
        return $this->updateContact($personRequest);
    }

    /**
     * @param \Cheppers\MiniCrm\DataTypes\Contact\Business\BusinessRequest $businessRequest
     *
     * @return array
     *
     * @throws \Exception
     */
    public function updateBusiness(BusinessRequest $businessRequest): array
    {
        return $this->updateContact($businessRequest);
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
    public function deletePerson(int $contactId): array
    {
        $response = $this->sendRequest(
            'GET',
            ContactRequestBase::__set_state([]),
            "/PurgePerson/{$contactId}"
        );

        return $this->validateAndParseResponse($response);
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

    /**
     * @param $request
     *
     * @return array
     *
     * @throws \Exception
     */
    protected function updateContact($request): array
    {
        if ($request instanceof PersonRequest) {
            $contact = $this->getPerson($request->id);
        } elseif ($request instanceof BusinessRequest) {
            $contact = $this->getBusiness($request->id);
        } else {
            throw new \Exception('Wrong request.', 1);
        }

        $path = "/Contact/{$request->id}";

        // Type can not be changed.
        $request->type = $contact->type;
        $response = $this->sendRequest('PUT', $request, $path);

        return $this->validateAndParseResponse($response);
    }
}
