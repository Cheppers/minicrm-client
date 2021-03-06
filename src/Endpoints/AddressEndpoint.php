<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Endpoints;

use Cheppers\MiniCrm\DataTypes\Address\AddressRequest;
use Cheppers\MiniCrm\DataTypes\Address\AddressResponse;
use Cheppers\MiniCrm\DataTypes\Address\StructuredAddressResponse;
use Cheppers\MiniCrm\DataTypes\RequestBase;
use Cheppers\MiniCrm\MiniCrmClient;

class AddressEndpoint extends MiniCrmClient implements EndpointInterface
{
    /**
     * @param int $addressId
     *
     * @return \Cheppers\MiniCrm\DataTypes\Address\AddressResponse
     *
     * @throws \Exception
     */
    public function get(int $addressId)
    {
        $path = "/Address/{$addressId}";

        $response = $this->sendRequest(
            'GET',
            AddressRequest::__set_state([]),
            $path
        );

        $body = $this->validateAndParseResponse($response);

        return AddressResponse::__set_state($body);
    }

    /**
     * @param \Cheppers\MiniCrm\DataTypes\Address\AddressRequest $addressRequest
     * @param bool $structured
     *
     * @return \Cheppers\MiniCrm\DataTypes\Address\AddressResponse|\Cheppers\MiniCrm\DataTypes\Address\StructuredAddressResponse
     *
     * @throws \Exception
     */
    public function getMultiple(AddressRequest $addressRequest, bool $structured = false)
    {
        $path = "/AddressList/{$addressRequest->contactId}";
        $path = $structured ? "{$path}?Structured=1" : $path;

        $response = $this->sendRequest(
            'GET',
            $addressRequest,
            $path
        );

        $body = $this->validateAndParseResponse($response);
        $addresses = $structured
            ? StructuredAddressResponse::__set_state($body)
            : AddressResponse::__set_state($body);

        return $addresses;
    }

    /**
     * @param \Cheppers\MiniCrm\DataTypes\RequestBase $addressRequest
     *
     * @return array
     *
     * @throws \Exception
     */
    public function create(RequestBase $addressRequest): array
    {
        $response = $this->sendRequest('PUT', $addressRequest, '/Address');

        return $this->validateAndParseResponse($response);
    }

    /**
     * @param \Cheppers\MiniCrm\DataTypes\RequestBase $addressRequest
     *
     * @return array
     *
     * @throws \Exception
     */
    public function update(RequestBase $addressRequest): array
    {
        $path = "/Address/{$addressRequest->id}";
        $address = $this->get($addressRequest->id);

        // ContactId can not be changed.
        $addressRequest->contactId = $address->contactId;
        $response = $this->sendRequest('PUT', $addressRequest, $path);

        return $this->validateAndParseResponse($response);
    }
}
