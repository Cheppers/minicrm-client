<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm;

use GuzzleHttp\ClientInterface;
use Psr\Log\LoggerInterface;

interface MiniCrmClientInterface
{
    /**
     * @param ClientInterface $client
     * @param LoggerInterface $logger
     */
    public function __construct(ClientInterface $client, LoggerInterface $logger);

    /**
     * @return string
     */
    public function getApiKey(): string;

    /**
     * @param $apiKey
     *
     * @return $this
     */
    public function setApiKey(string $apiKey);

    /**
     * @return string
     */
    public function getSystemId(): string;

    /**
     * @param $systemId
     *
     * @return $this
     */
    public function setSystemId(string $systemId);

    /**
     * @return string
     */
    public function getBaseUri(): string;

    /**
     * @param $baseUri
     *
     * @return $this
     */
    public function setBaseUri(string $baseUri);

    /**
     * @param array $options
     *
     * @return $this
     */
    public function setCredentials(array $options);
}
