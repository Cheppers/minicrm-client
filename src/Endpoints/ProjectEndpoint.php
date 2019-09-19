<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Endpoints;

use Cheppers\MiniCrm\DataTypes\Project\SingleProjectResponse;
use Cheppers\MiniCrm\DataTypes\Project\ProjectRequest;
use Cheppers\MiniCrm\DataTypes\Project\ProjectResponse;
use Cheppers\MiniCrm\MiniCrmClient;

class ProjectEndpoint extends MiniCrmClient
{
    /**
     * @param int $projectId
     *
     * @return \Cheppers\MiniCrm\DataTypes\Project\SingleProjectResponse
     *
     * @throws \Exception
     */
    public function getProject(int $projectId): SingleProjectResponse
    {
        $path = "/Project/{$projectId}";

        $response = $this->sendRequest(
            'GET',
            ProjectRequest::__set_state([]),
            $path
        );

        $body = $this->validateAndParseResponse($response);
        $project = SingleProjectResponse::__set_state($body);

        return $project;
    }

    /**
     * @param int $categoryId
     *
     * @return \Cheppers\MiniCrm\DataTypes\Project\ProjectResponse
     * @throws \Exception
     */
    public function getProjectsByCategoryId(int $categoryId): ProjectResponse
    {
        $path = "/Project?CategoryId={$categoryId}";

        $response = $this->sendRequest(
            'GET',
            ProjectRequest::__set_state([]),
            $path
        );

        $body = $this->validateAndParseResponse($response);
        $projects = ProjectResponse::__set_state($body);

        return $projects;
    }

    /**
     * @param string $statusGroup
     *
     * @return \Cheppers\MiniCrm\DataTypes\Project\ProjectResponse
     * @throws \Exception
     */
    public function getProjectsByStatusGroup(string $statusGroup)
    {
        $path = "/Project?StatusGroup={$statusGroup}";

        $response = $this->sendRequest(
            'GET',
            ProjectRequest::__set_state([]),
            $path
        );

        $body = $this->validateAndParseResponse($response);
        $projects = ProjectResponse::__set_state($body);

        return $projects;
    }

    /**
     * @param int $userId
     *
     * @return \Cheppers\MiniCrm\DataTypes\Project\ProjectResponse
     * @throws \Exception
     */
    public function getProjectsByUserId(int $userId)
    {
        $path= "/Project?UserId={$userId}";

        $response = $this->sendRequest(
            'GET',
            ProjectRequest::__set_state([]),
            $path
        );

        $body = $this->validateAndParseResponse($response);
        $projects = ProjectResponse::__set_state($body);

        return $projects;
    }

    /**
     * @param \Cheppers\MiniCrm\DataTypes\Project\ProjectRequest $projectRequest
     *
     * @return array
     *
     * @throws \Exception
     */
    public function createProject(ProjectRequest $projectRequest): array
    {
        $path = "/Project";

        $response = $this->sendRequest(
            'PUT',
            $projectRequest,
            $path
        );

        $body = $this->validateAndParseResponse($response);

        return $body;
    }
}
