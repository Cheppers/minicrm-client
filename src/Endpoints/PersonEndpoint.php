<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Endpoints;

use Cheppers\MiniCrm\DataTypes\Contact\ContactRequestBase;
use Cheppers\MiniCrm\DataTypes\Contact\Person\PersonResponse;
use Cheppers\MiniCrm\DataTypes\RequestBase;
use Cheppers\MiniCrm\MiniCrmClient;

class PersonEndpoint extends MiniCrmClient implements EndpointInterface
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
    public function get(int $contactId): PersonResponse
    {
        $response = $this->sendRequest(
            'GET',
            ContactRequestBase::__set_state([]),
            "/Contact/{$contactId}"
        );

        $body =  $this->validateAndParseResponse($response);

        return PersonResponse::__set_state($body);
    }

    /**
     * @param \Cheppers\MiniCrm\DataTypes\RequestBase $personRequest
     *
     * @return array
     *
     * @throws \Exception
     */
    public function create(RequestBase $personRequest): array
    {
        $response = $this->sendRequest('PUT', $personRequest, '/Contact');

        return $this->validateAndParseResponse($response);
    }

    /**
     * @param \Cheppers\MiniCrm\DataTypes\RequestBase $personRequest
     *
     * @return array
     *
     * @throws \Exception
     */
    public function update(RequestBase $personRequest): array
    {
        $person = $this->get($personRequest->id);

        $path = "/Contact/{$personRequest->id}";

        // Type can not be changed.
        $personRequest->type = $person->type;
        $response = $this->sendRequest('PUT', $personRequest, $path);

        return $this->validateAndParseResponse($response);
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
    public function delete(int $contactId): array
    {
        $response = $this->sendRequest(
            'GET',
            ContactRequestBase::__set_state([]),
            "/PurgePerson/{$contactId}"
        );

        return $this->validateAndParseResponse($response);
    }
}
