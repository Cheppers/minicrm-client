<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Endpoints;

use Cheppers\MiniCrm\DataTypes\Contact\BusinessResponse;
use Cheppers\MiniCrm\DataTypes\Contact\ContactRequest;
use Cheppers\MiniCrm\DataTypes\Contact\PersonResponse;
use Cheppers\MiniCrm\MiniCrmClient;

class ContactEndpoint extends MiniCrmClient
{

    /**
     * @param int $contactId
     *
     * @return \Cheppers\MiniCrm\DataTypes\Contact\PersonResponse
     * @throws \Exception
     */
    public function getPerson(int $contactId): PersonResponse
    {
        $body = $this->getContact($contactId);
        $contact = PersonResponse::__set_state($body);

        return $contact;
    }

    /**
     * @param int $contactId
     *
     * @return \Cheppers\MiniCrm\DataTypes\Contact\BusinessResponse
     * @throws \Exception
     */
    public function getBusiness(int $contactId): BusinessResponse
    {
        $body = $this->getContact($contactId);
        $contact = BusinessResponse::__set_state($body);

        return $contact;
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
        $path = "/Contact/{$contactId}";

        $response = $this->sendRequest(
            'GET',
            ContactRequest::__set_state([]),
            $path
        );

        return $this->validateAndParseResponse($response);
    }
}
