<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
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

    /**
     * @param string $path
     *
     * @return string
     */
    protected function getUri(string $path)
    {
        return $this->getBaseUri() . '/Api/R3' . $path;
    }

    /**
     * @param array $base
     *
     * @return array
     */
    protected function getRequestHeaders(array $base = []): array
    {
        $auth = base64_encode($this->systemId . ':' . $this->apiKey);
        $base += [
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . $auth,
        ];

        return $base;
    }

    /**
     * @param string $path
     *
     * @return ResponseInterface|null
     */
    protected function get(string $path): ?ResponseInterface
    {
        return $this->sendRequest('GET', $path);
    }

    /**
     * @param string $path
     * @param array $options
     *
     * @return ResponseInterface|null
     */
    protected function put(string $path, array $options = []): ?ResponseInterface
    {
        return $this->sendRequest('PUT', $path, $options);
    }

    /**
     * @param string $method
     * @param string $path
     * @param array $options
     *
     * @return ResponseInterface|null
     */
    protected function sendRequest(string $method, string $path, array $options = []): ?ResponseInterface
    {
        $options += [
            'headers' => [],
        ];
        $options['headers'] += $this->getRequestHeaders();

        try {
            /** @var \Psr\Http\Message\ResponseInterface $response */
            $response = $this->client->request($method, $this->getUri($path), $options);
        } catch (GuzzleException $e) {
            $this->logger->error($e->getMessage());
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }

        if (!isset($response)) {
            return null;
        }

        return $response;
    }

    /**
     * @param $response
     *
     * @return mixed
     *
     * @throws \Exception
     */
    protected function validateResponse($response)
    {
        if (is_null($response)) {
            throw new \Exception('Invalid response.', 1);
        }

        /** @var ResponseInterface $response */
        $contentType = $response->getHeader('Content-Type');
        if (end($contentType) !== 'application/json; charset=utf-8') {
            throw new \Exception('Invalid Content-Type.', 1);
        }

        return $response;
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return array
     */
    protected function parseResponse(ResponseInterface $response): array
    {
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param $response
     *
     * @return array
     */
    protected function validateAndParseResponse($response): array
    {
        try {
            $this->validateResponse($response);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }

        return $this->parseResponse($response);
    }
}
