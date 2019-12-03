<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Endpoints;

use Cheppers\MiniCrm\DataTypes\Project\ProjectEmailsResponse;
use Cheppers\MiniCrm\DataTypes\Project\SingleProjectResponse;
use Cheppers\MiniCrm\DataTypes\Project\ProjectRequest;
use Cheppers\MiniCrm\DataTypes\Project\ProjectResponse;
use Cheppers\MiniCrm\DataTypes\RequestBase;
use Cheppers\MiniCrm\MiniCrmClient;

class ProjectEndpoint extends MiniCrmClient implements EndpointInterface
{
    /**
     * @param int $projectId
     *
     * @return \Cheppers\MiniCrm\DataTypes\Project\SingleProjectResponse
     *
     * @throws \Exception
     */
    public function get(int $projectId): SingleProjectResponse
    {
        $body = $this->getMultiple(
            "/Project/{$projectId}",
            ProjectRequest::__set_state([])
        );

        return SingleProjectResponse::__set_state($body);
    }

    /**
     * @param int $categoryId
     *
     * @return \Cheppers\MiniCrm\DataTypes\Project\ProjectResponse
     *
     * @throws \Exception
     */
    public function getByCategoryId(int $categoryId): ProjectResponse
    {
        $body = $this->getMultiple(
            "/Project?CategoryId={$categoryId}",
            ProjectRequest::__set_state([])
        );

        return ProjectResponse::__set_state($body);
    }

    /**
     * @param string $statusGroup
     *
     * @return \Cheppers\MiniCrm\DataTypes\Project\ProjectResponse
     *
     * @throws \Exception
     */
    public function getByStatusGroup(string $statusGroup): ProjectResponse
    {
        $body = $this->getMultiple(
            "/Project?StatusGroup={$statusGroup}",
            ProjectRequest::__set_state([])
        );

        return ProjectResponse::__set_state($body);
    }

    /**
     * @param int $userId
     *
     * @return \Cheppers\MiniCrm\DataTypes\Project\ProjectResponse
     *
     * @throws \Exception
     */
    public function getByUserId(int $userId): ProjectResponse
    {
        $body = $this->getMultiple(
            "/Project?UserId={$userId}",
            ProjectRequest::__set_state([])
        );

        return ProjectResponse::__set_state($body);
    }

    /**
     * @param \Cheppers\MiniCrm\DataTypes\Project\ProjectRequest $projectRequest
     *
     * @return \Cheppers\MiniCrm\DataTypes\Project\ProjectEmailsResponse
     *
     * @throws \Exception
     */
    public function getEmails(ProjectRequest $projectRequest): ProjectEmailsResponse
    {
        $body = $this->getMultiple(
            "/EmailList/{$projectRequest->id}",
            $projectRequest
        );

        return ProjectEmailsResponse::__set_state($body);
    }

    /**
     * @param \Cheppers\MiniCrm\DataTypes\RequestBase $projectRequest
     *
     * @return array
     *
     * @throws \Exception
     */
    public function create(RequestBase $projectRequest): array
    {
        $path = "/Project";

        $response = $this->sendRequest(
            'PUT',
            $projectRequest,
            $path
        );

        return $this->validateAndParseResponse($response);
    }

    /**
     * @param $path
     * @param $request
     *
     * @return array
     *
     * @throws \Exception
     */
    protected function getMultiple($path, $request): array
    {
        $response = $this->sendRequest(
            'GET',
            $request,
            $path
        );

        return $this->validateAndParseResponse($response);
    }

    /**
     * @param \Cheppers\MiniCrm\DataTypes\RequestBase $projectRequest
     *
     * @return array
     *
     * @throws \Exception
     */
    public function update(RequestBase $projectRequest): array
    {
        $path = "/Project/{$projectRequest->id}";
        $project = $this->get($projectRequest->id);

        // CategoryId and ContactId can not be changed.
        $projectRequest->categoryId = $project->categoryId;
        $projectRequest->contactId = $project->contactId;

        $response = $this->sendRequest(
            'PUT',
            $projectRequest,
            $path
        );

        return $this->validateAndParseResponse($response);
    }
}
