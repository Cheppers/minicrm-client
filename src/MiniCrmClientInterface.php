<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm;

use GuzzleHttp\ClientInterface;

interface MiniCrmClientInterface
{
    /**
     * MiniCrmClientInterface constructor.
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client);

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getResponse();

    /**
     * @return string
     */
    public function getApiKey();

    /**
     * @param $apiKey
     * @return $this
     */
    public function setApiKey($apiKey);

    /**
     * @return int
     */
    public function getSystemId();

    /**
     * @param $systemId
     * @return $this
     */
    public function setSystemId($systemId);

    /**
     * @return string
     */
    public function getBaseUri();

    /**
     * @param $baseUri
     * @return $this
     */
    public function setBaseUri($baseUri);

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options);

    /**
     * @return int|MiniCrmClientException
     * @throws MiniCrmClientException
     */
    public function id();

    /**
     * @return mixed
     * @throws MiniCrmClientException
     */
    public function fetch();
}
