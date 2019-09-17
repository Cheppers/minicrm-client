<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm;

use GuzzleHttp\ClientInterface;
use Psr\Log\LoggerInterface;

class MiniCrmClient implements MiniCrmClientInterface
{
    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $client;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var string
     */
    protected $baseUri = '';

    /**
     * @var string
     */
    protected $apiKey = '';

    /**
     * @var integer
     */
    protected $systemId;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        ClientInterface $client,
        LoggerInterface $logger
    ) {
        $this->client = $client;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseUri(): string
    {
        return $this->baseUri;
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseUri(string $baseUri): MiniCrmClient
    {
        $this->baseUri = $baseUri;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * {@inheritdoc}
     */
    public function setApiKey(string $apiKey): MiniCrmClient
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSystemId(): int
    {
        return $this->systemId;
    }

    /**
     * {@inheritdoc}
     */
    public function setSystemId(int $systemId): MiniCrmClient
    {
        $this->systemId = $systemId;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setCredentials(array $options): MiniCrmClient
    {
        foreach ($options as $key => $value) {
            switch ($key) {
                case 'baseUri':
                    $this->setBaseUri($value);
                    break;
                case 'apiKey':
                    $this->setApiKey($value);
                    break;
                case 'systemId':
                    $this->setSystemId($value);
                    break;
                default:
                    break;
            }
        }

        return $this;
    }
}
