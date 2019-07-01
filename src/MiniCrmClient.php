<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm;

use DateTime;
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
                MiniCrmClientException::WRONG_DATA_PROVIDED
            );
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

    /**
     * @param null $categoryId
     * @param null $page
     * @param null $businessId
     * @return mixed
     * @throws MiniCrmClientException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getProject($categoryId = null, $page = null, $businessId = null)
    {
        if (!is_null($categoryId) && !is_int($categoryId)) {
            throw new MiniCrmClientException(
                'The category ID you provided is invalid. Please use only numbers.',
                MiniCrmClientException::WRONG_DATA_PROVIDED
            );
        } elseif (!is_null($categoryId) && is_int($categoryId)) {
            $vCategoryId = "&CategoryId={$categoryId}";
        } else {
            $vCategoryId = '';
        }

        if (!is_null($page) && is_int($page)) {
            $vPage = "&Page={$page}";
        } else {
            $vPage = '';
        }

        if (!is_null($businessId) && !is_int($businessId)) {
            throw new MiniCrmClientException(
                'The business ID you provided is invalid. Please use only numbers.',
                MiniCrmClientException::WRONG_DATA_PROVIDED
            );
        } elseif (!is_null($businessId) && is_int($businessId)) {
            $vBusinessId = "&MainContactId={$businessId}";
        } else {
            $vBusinessId = '';
        }

        $this->sendGet("/Api/R3/Project?{$vCategoryId}{$vPage}{$vBusinessId}");
        $body = $this->parseResponse();

        return $body;
    }

    /**
     * @param null $email
     * @param null $updatedSince
     * @param null $searchString
     * @param int|null $businessId
     * @return mixed
     * @throws MiniCrmClientException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getContact(
        $email = null,
        $updatedSince = null,
        $searchString = null,
        $businessId = null
    ) {
        // Check if any parameter is provided or all of them are NULL.
        if (is_null($email) && is_null($updatedSince) && is_null($searchString) && is_null($businessId)) {
            throw new MiniCrmClientException(
                'Please provide at least 1 parameter to search for.',
                MiniCrmClientException::NO_DATA
            );
        }

        // Validate E-mail address.
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $vEmail = $email;
        } elseif (is_null($email)) {
            $vEmail = '';
        } else {
            throw new MiniCrmClientException(
                'The provided email is not valid. Please use a valid e-mail address.',
                MiniCrmClientException::WRONG_DATA_PROVIDED
            );
        }

        // Validate Update Date.
        if (!is_null($updatedSince)) {
            if ($this->isValidDate($updatedSince)) {
                $vUpdatedSince = $updatedSince;
            } else {
                throw new MiniCrmClientException(
                    'The provided update date is invalid. Please use the \'2012-12-12+12:12:12\' format.',
                    MiniCrmClientException::WRONG_DATA_PROVIDED
                );
            }
        } else {
            $vUpdatedSince = '';
        }

        // Sanitize search string.
        if (!is_null($searchString)) {
            $vSearchString = '&Query=' . filter_var($searchString, FILTER_SANITIZE_STRING);
        } else {
            $vSearchString = '';
        }

        if (!is_null($businessId) && !is_int($businessId)) {
            throw new MiniCrmClientException(
                'The business ID you provided is invalid. Please use only numbers.',
                MiniCrmClientException::WRONG_DATA_PROVIDED
            );
        } elseif (!is_null($businessId) && is_int($businessId)) {
            $vBusinessId = "&MainContactId={$businessId}";
        } else {
            $vBusinessId = '';
        }

        $this->sendGet("/Api/R3/Contact?Email={$vEmail}&UpdatedSince={$vUpdatedSince}{$vSearchString}{$vBusinessId}");
        $body = $this->parseResponse();

        return $body;
    }

    public function isValidDate($date, $format = 'Y-m-d\+H:i:s')
    {
        $dateObj = DateTime::createFromFormat($format, $date);
        return $dateObj && $dateObj->format($format) == $date;
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
