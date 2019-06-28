<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm;

use GuzzleHttp\ClientInterface;

interface MiniCrmClientInterface
{
    public function __construct(ClientInterface $client);

    public function getResponse();

    public function getApiKey();

    public function setApiKey($apiKey);

    public function getSystemId();

    public function setSystemId($systemId);

    public function getBaseUri();

    public function setBaseUri($baseUri);

    /**
     * @return \Cheppers\MiniCrm\DataTypes\Category[]
     * @see "https://r3.minicrm.hu/Api/R3/Category"
     */
    public function getCategories();
}
