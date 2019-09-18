<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Endpoints;

use Cheppers\MiniCrm\DataTypes\Address\Addresses;
use Cheppers\MiniCrm\MiniCrmClient;

class AddressEndpoint extends MiniCrmClient
{
    /**
     * @param int $contactId
     *
     * @return \Cheppers\MiniCrm\DataTypes\Address\Addresses
     */
    public function getAddresses(int $contactId): Addresses
    {
        $response = $this->get("/AddressList/{$contactId}");

        $body = $this->validateAndParseResponse($response);
        $addresses = Addresses::__set_state($body);

        return $addresses;
    }

    /**
     * @param int $contactId
     *
     * @return \Cheppers\MiniCrm\DataTypes\Address\Addresses
     */
    public function getStructuredAddresses(int $contactId): Addresses
    {
        $response = $this->get("/AddressList/{$contactId}?Structured=1");

        $body = $this->validateAndParseResponse($response);
        $addresses = Addresses::__set_state($body);

        return $addresses;
    }
}
