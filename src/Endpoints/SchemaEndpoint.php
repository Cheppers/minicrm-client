<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Endpoints;

use Cheppers\MiniCrm\DataTypes\Schema\SchemaRequest;
use Cheppers\MiniCrm\DataTypes\Schema\SchemaResponse;
use Cheppers\MiniCrm\MiniCrmClient;

class SchemaEndpoint extends MiniCrmClient
{

    /**
     * @param int $projectId
     *
     * @return \Cheppers\MiniCrm\DataTypes\Schema\SchemaResponse
     *
     * @throws \Exception
     */
    public function getProjectSchema(int $projectId): SchemaResponse
    {
        return $this->getSchema(
            "/Schema/Project/{$projectId}",
            SchemaRequest::__set_state([])
        );
    }

    /**
     * @return \Cheppers\MiniCrm\DataTypes\Schema\SchemaResponse
     *
     * @throws \Exception
     */
    public function getPersonSchema(): SchemaResponse
    {
        return $this->getSchema(
            '/Schema/Person',
            SchemaRequest::__set_state([])
        );
    }

    /**
     * @return \Cheppers\MiniCrm\DataTypes\Schema\SchemaResponse
     *
     * @throws \Exception
     */
    public function getBusinessSchema(): SchemaResponse
    {
        return $this->getSchema(
            '/Schema/Business',
            SchemaRequest::__set_state([])
        );
    }

    /**
     * @param string $path
     * @param \Cheppers\MiniCrm\DataTypes\Schema\SchemaRequest $schemaRequest
     *
     * @return \Cheppers\MiniCrm\DataTypes\Schema\SchemaResponse
     *
     * @throws \Exception
     */
    protected function getSchema(string $path, SchemaRequest $schemaRequest): SchemaResponse
    {
        $response = $this->sendRequest(
            'GET',
            $schemaRequest,
            $path
        );

        $body = $this->validateAndParseResponse($response);

        $categories = SchemaResponse::__set_state($body);

        return $categories;
    }
}
