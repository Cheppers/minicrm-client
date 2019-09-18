<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Endpoints;

use Cheppers\MiniCrm\DataTypes\Address\AddressRequest;
use Cheppers\MiniCrm\DataTypes\Address\AddressResponse;
use Cheppers\MiniCrm\DataTypes\Address\StructuredAddressResponse;
use Cheppers\MiniCrm\MiniCrmClient;

class AddressEndpoint extends MiniCrmClient
{
    /**
     * @param \Cheppers\MiniCrm\DataTypes\Address\AddressRequest $addressRequest
     *
     * @return \Cheppers\MiniCrm\DataTypes\Address\AddressResponse
     *
     * @throws \Exception
     */
    public function getAddresses(AddressRequest $addressRequest): AddressResponse
    {
        $response = $this->sendRequest(
            'GET',
            $addressRequest,
            "/AddressList/{$addressRequest->contactId}"
        );

        $body = $this->validateAndParseResponse($response);
        $addresses = AddressResponse::__set_state($body);

        return $addresses;
    }

    /**
     * @param \Cheppers\MiniCrm\DataTypes\Address\AddressRequest $addressRequest
     *
     * @return \Cheppers\MiniCrm\DataTypes\Address\StructuredAddressResponse
     *
     * @throws \Exception
     */
    public function getStructuredAddresses(AddressRequest $addressRequest): StructuredAddressResponse
    {
        $response = $this->sendRequest(
            'GET',
            $addressRequest,
            "/AddressList/{$addressRequest->contactId}?Structured=1"
        );

        $body = $this->validateAndParseResponse($response);
        $addresses = StructuredAddressResponse::__set_state($body);

        return $addresses;
    }

    /**
     * @param \Cheppers\MiniCrm\DataTypes\Address\AddressRequest $addressRequest
     *
     * @return array
     *
     * @throws \Exception
     */
    public function createAddress(AddressRequest $addressRequest): array
    {
        $response = $this->sendRequest('PUT', $addressRequest, '/Address');
        $body = $this->validateAndParseResponse($response);

        return $body;
    }
}
