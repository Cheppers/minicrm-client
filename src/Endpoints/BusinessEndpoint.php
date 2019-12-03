<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Endpoints;

use Cheppers\MiniCrm\DataTypes\Contact\Business\BusinessResponse;
use Cheppers\MiniCrm\DataTypes\Contact\ContactRequestBase;
use Cheppers\MiniCrm\DataTypes\RequestBase;
use Cheppers\MiniCrm\MiniCrmClient;

class BusinessEndpoint extends MiniCrmClient implements EndpointInterface
{
    /**
     * Gets back a Business based on it's contactId.
     *
     * @param int $contactId
     *
     * @return \Cheppers\MiniCrm\DataTypes\Contact\Business\BusinessResponse
     *
     * @throws \Exception
     */
    public function get(int $contactId): BusinessResponse
    {
        $response = $this->sendRequest(
            'GET',
            ContactRequestBase::__set_state([]),
            "/Contact/{$contactId}"
        );

        $body = $this->validateAndParseResponse($response);

        return BusinessResponse::__set_state($body);
    }

    /**
     * @param \Cheppers\MiniCrm\DataTypes\RequestBase $businessRequest
     *
     * @return array
     *
     * @throws \Exception
     */
    public function create(RequestBase $businessRequest): array
    {
        $response = $this->sendRequest('PUT', $businessRequest, '/Contact');

        return $this->validateAndParseResponse($response);
    }

    /**
     * @param \Cheppers\MiniCrm\DataTypes\RequestBase $businessRequest
     *
     * @return array
     *
     * @throws \Exception
     */
    public function update(RequestBase $businessRequest): array
    {
        $business = $this->get($businessRequest->id);

        $path = "/Contact/{$businessRequest->id}";

        // Type can not be changed.
        $businessRequest->type = $business->type;
        $response = $this->sendRequest('PUT', $businessRequest, $path);

        return $this->validateAndParseResponse($response);
    }
}
