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
     * @param bool $structured
     *
     * @return \Cheppers\MiniCrm\DataTypes\Address\AddressResponse|\Cheppers\MiniCrm\DataTypes\Address\StructuredAddressResponse
     *
     * @throws \Exception
     */
    public function getAddresses(AddressRequest $addressRequest, bool $structured = false)
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
