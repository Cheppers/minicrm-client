<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm;

use GuzzleHttp\ClientInterface;

class MiniCrmClient implements MiniCrmClientInterface
{
    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $client;

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

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
     * MiniCrmClient constructor.
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return string
     */
    public function getBaseUri()
    {
        return $this->baseUri;
    }

    /**
     * @param $baseUri
     * @return $this
     */
    public function setBaseUri($baseUri)
    {
        $this->baseUri = $baseUri;

        return $this;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param $apiKey
     * @return $this
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * @return int
     */
    public function getSystemId()
    {
        return $this->systemId;
    }

    /**
     * @param $systemId
     * @return $this
     */
    public function setSystemId($systemId)
    {
        $this->systemId = $systemId;

        return $this;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options)
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
            }
        }

        return $this;
    }

    /**
     * @return mixed
     * @throws MiniCrmClientException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCategories()
    {
        $this->sendGet('/Api/R3/Category');
        $body = $this->parseResponse();

        return $body;
    }

    /**
     * @param $path
     * @return string
     */
    protected function getUri($path)
    {
        return $this->getBaseUri() . $path;
    }

    /**
     * @return string
     */
    protected function getRequestAuth()
    {
        $credentials = [
            'systemId' => $this->getSystemId(),
            'apiKey' => $this->getApiKey(),
        ];

        $auth = base64_encode("{$credentials['systemId']}:{$credentials['apiKey']}");

        return $auth;
    }

    /**
     * @param array $base
     * @return array
     */
    protected function getRequestHeaders(array $base = [])
    {
        return $base + [
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . $this->getRequestAuth(),
        ];
    }

    /**
     * @param $path
     * @param array $options
     * @return MiniCrmClient
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function sendGet($path, array $options = [])
    {
        return $this->sendRequest('GET', $path, $options);
    }

    /**
     * @param $path
     * @param array $options
     * @return MiniCrmClient
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function sendPost($path, array $options = [])
    {
        return $this->sendRequest('POST', $path, $options);
    }

    /**
     * @param $method
     * @param $path
     * @param array $options
     * @return $this
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function sendRequest($method, $path, array $options = [])
    {
        $options += [
            'headers' => [],
        ];
        $options['headers'] += $this->getRequestHeaders();
        $uri = $this->getUri($path);
        $this->response = $this->client->request($method, $uri, $options);

        return $this;
    }

    /**
     * @return mixed
     * @throws MiniCrmClientException
     */
    protected function parseResponse()
    {
        $body = json_decode($this->response->getBody()->getContents(), true);
        if (empty($body) || is_null($body)) {
            throw new MiniCrmClientException(
                'No data found.',
                MiniCrmClientException::NO_DATA
            );
        }
        return $body;
    }
}
