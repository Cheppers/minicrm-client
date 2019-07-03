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

    protected $id;

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
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function id()
    {
        return isset($this->id) ? $this->id : NULL;
    }

    /**
     * {@inheritdoc}
     */
    public function fetch()
    {
        return $this->parseResponse();
    }

    /**
     * @param $type
     * @return $this
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
        }

        return $this;
    }

    /**
     * @return $this
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCategories()
    {
        $this->sendGet('/Api/R3/Category');

        return $this;
    }

    /**
     * @param null $categoryId
     * @param null $page
     * @param null $businessId
     * @return $this
     * @throws MiniCrmClientException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getProject($categoryId = null, $page = null, $businessId = null, $name = null)
    {
        if (is_null($categoryId) && is_null($businessId)) {
            throw new MiniCrmClientException(
                'Please use at least 1 parameter (either category ID or business ID) in the request.',
                MiniCrmClientException::NO_DATA
            );
        }

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

        if (!is_null($name)) {
            $name = filter_var($name, FILTER_SANITIZE_STRING);
            $vName = "&Name={$name}";
            $this->id = $this->getProjectId($name);
        } else {
            $vName = '';
        }

        $this->sendGet("/Api/R3/Project?{$vCategoryId}{$vPage}{$vBusinessId}{$vName}");

        return $this;
    }

    /**
     * @param $name
     * @return mixed
     * @throws MiniCrmClientException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getProjectId($name)
    {
        $name = filter_var($name, FILTER_SANITIZE_STRING);

        $this->sendGet("/Api/R3/Project?Name={$name}");
        $body = $this->parseResponse();

        if (!isset($body['Results'])) {
            throw new MiniCrmClientException(
                'Unexpected answer. Could not fetch contact ID',
                MiniCrmClientException::UNEXPECTED_ANSWER
            );
        } elseif (empty($body['Results'])) {
            throw new MiniCrmClientException(
                "There is no person with the name '{$name}'.",
                MiniCrmClientException::NO_DATA
            );
        } else {
            $id = array_key_first($body['Results']);
        }

        return $id;
    }

    /**
     * @param string $name
     * @param string $userId
     * @param int $categoryId
     * @param int $contactId
     * @param int $statusId
     * @return string
     * @throws MiniCrmClientException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * You can attach a business to a partner (contact) which can be attached to a project.
     */
    public function createProject(string $name, string $userId, int $categoryId, int $contactId, int $statusId)
    {
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $userId = filter_var($userId, FILTER_SANITIZE_STRING);

        $data = [
            'json' => [
                'Name' => $name,
                'UserId' => $userId,
                'CategoryId' => $categoryId,
                'ContactId' => $contactId,
                'StatusId' => $statusId
            ],
        ];

        $this->sendPut('/Api/R3/Project', $data);

        if ($this->response->getStatusCode() !== 200) {
            $responseContentType = $this->response->getHeader('Content-Type');
            $responseContentType = end($responseContentType);
            if ($responseContentType === 'application/json') {
                $this->parseResponse();
            }
            throw new MiniCrmClientException(
                'Unexpected answer',
                MiniCrmClientException::UNEXPECTED_ANSWER
            );
        } else {
            $result = 'Project created.';
        }

        return $result;
    }

    /**
     * @param null $email
     * @param null $updatedSince
     * @param null $searchString
     * @param int|null $businessId
     * @return $this
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

        $this->id = $this->getContactId($vEmail);
        $this->sendGet("/Api/R3/Contact?Email={$vEmail}&UpdatedSince={$vUpdatedSince}{$vSearchString}{$vBusinessId}");

        return $this;
    }

    /**
     * @param $email
     * @return $this
     * @throws MiniCrmClientException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getContactId($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new MiniCrmClientException(
                'Could not fetch ID from the given data.',
                MiniCrmClientException::WRONG_DATA_PROVIDED
            );
        } else {
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        }

        $this->sendGet("/Api/R3/Contact?Type=Person&Email={$email}");
        $body = $this->parseResponse();

        if (!isset($body['Results'])) {
            throw new MiniCrmClientException(
                'Unexpected answer. Could not fetch contact ID',
                MiniCrmClientException::UNEXPECTED_ANSWER
            );
        } elseif (empty($body['Results'])) {
            throw new MiniCrmClientException(
                "There is no person with the email address '{$email}'.",
                MiniCrmClientException::NO_DATA
            );
        } else {
            $id = array_key_first($body['Results']);
        }

        return $id;
    }

    /**
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param null $phone
     * @param null $businessId
     * @return string
     * @throws MiniCrmClientException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createContact(
        string $firstName,
        string $lastName,
        string $email,
        $phone = null,
        $businessId = null
    ) {
        $firstName = filter_var($firstName, FILTER_SANITIZE_STRING);
        $lastName = filter_var($lastName, FILTER_SANITIZE_STRING);

        $data = [
            'json' => [
                'Type' => 'Person',
                'FirstName' => $firstName,
                'LastName' => $lastName
            ],
        ];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new MiniCrmClientException(
                'Please provide a valid email address.',
                MiniCrmClientException::WRONG_DATA_PROVIDED
            );
        } else {
            $data['json']['Email'] = $email;
        }

        if (!preg_match('/^\+[0,9]{0,20}/', $phone)) {
            throw new MiniCrmClientException(
                'Please provide a phone number like "+36301234567"',
                MiniCrmClientException::WRONG_DATA_PROVIDED
            );
        } else {
            $data['json']['Phone'] = $phone;
        }

        if (!is_null($businessId) && !is_int($businessId)) {
            throw new MiniCrmClientException(
                'The business ID you provided is invalid. Please use only numbers.',
                MiniCrmClientException::WRONG_DATA_PROVIDED
            );
        } elseif (!is_null($businessId) && is_int($businessId)) {
            $data['json']['BusinessId'] = $businessId;
        } else {
            $businessId = null;
        }

        $this->sendPut("/Api/R3/Contact", $data);

        if ($this->response->getStatusCode() !== 200) {
            $responseContentType = $this->response->getHeader('Content-Type');
            $responseContentType = end($responseContentType);
            if ($responseContentType === 'application/json') {
                $this->parseResponse();
            }
            throw new MiniCrmClientException(
                'Unexpected answer',
                MiniCrmClientException::UNEXPECTED_ANSWER
            );
        } else {
            $result = 'Contact created.';
        }

        return $result;
    }

    /**
     * @param int $contactId
     * @return mixed
     * @throws MiniCrmClientException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @todo Handle Exception when searched id couldn't be found.
     */
    public function deleteContact(int $contactId)
    {
        $this->sendGet("/Api/R3/PurgePerson/{$contactId}");
        $body = $this->parseResponse();

        if (isset($body['Message'])) {
            return $body['Message'];
        } else {
            throw new MiniCrmClientException(
                'Unexpected answer. Deletion failed.',
                MiniCrmClientException::UNEXPECTED_ANSWER
            );
        }
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
    protected function sendPut($path, array $options = [])
    {
        return $this->sendRequest('PUT', $path, $options);
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
