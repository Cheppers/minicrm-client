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
     * {@inheritdoc}
     */
    protected $systemId;

    /**
     * {@inheritdoc}
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseUri()
    {
        return $this->baseUri;
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseUri($baseUri)
    {
        $this->baseUri = $baseUri;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * {@inheritdoc}
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSystemId()
    {
        return $this->systemId;
    }

    /**
     * {@inheritdoc}
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
     * @param $type
     * @return mixed
     * @throws MiniCrmClientException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * Type could be either 'Business', 'Person' or 'Project/$CategoryID'.
     */
    public function getSchema($type)
    {
        if (!preg_match('/(Business|Person|Project\/[0-9]{1,9})/', $type)) {
            throw new MiniCrmClientException(
                'The data (type) you provided is invalid. Please use either "Business", "Person" or "Project/$ID".',
                MiniCrmClientException::WRONG_DATA_PROVIDED);
        } else {
            $this->sendGet("/Api/R3/Schema/{$type}");

            $body = $this->parseResponse();

            return $body;
        }

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

    public function getProject(int $categoryId, $page = NULL)
    {
        if (!is_int($categoryId)) {
            throw new MiniCrmClientException(
                'The category ID you provided is invalid. Please use only numbers.',
                MiniCrmClientException::WRONG_DATA_PROVIDED
            );
        } else {
            if (!is_null($page) && is_int($page)) {
                $pageParam = "&Page={$page}";
            } else {
                $pageParam = '';
            }
            $this->sendGet("/Api/R3/Project?CategoryId={$categoryId}{$pageParam}");
            $body = $this->parseResponse();

            return $body;
        }
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
