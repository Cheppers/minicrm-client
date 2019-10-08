<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Endpoints;

use Cheppers\MiniCrm\DataTypes\Project\ProjectEmailsResponse;
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
        $body = $this->getProjects(
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
    public function getProjectsByCategoryId(int $categoryId): ProjectResponse
    {
        $body = $this->getProjects(
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
    public function getProjectsByStatusGroup(string $statusGroup): ProjectResponse
    {
        $body = $this->getProjects(
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
    public function getProjectsByUserId(int $userId): ProjectResponse
    {
        $body = $this->getProjects(
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
    public function getProjectEmails(ProjectRequest $projectRequest): ProjectEmailsResponse
    {
        $body = $this->getProjects(
            "/EmailList/{$projectRequest->id}",
            $projectRequest
        );

        return ProjectEmailsResponse::__set_state($body);
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
    protected function getProjects($path, $request): array
    {
        $response = $this->sendRequest(
            'GET',
            $request,
            $path
        );

        return $this->validateAndParseResponse($response);
    }

    /**
     * @param \Cheppers\MiniCrm\DataTypes\Project\ProjectRequest $projectRequest
     *
     * @return array
     *
     * @throws \Exception
     */
    public function updateProject(ProjectRequest $projectRequest): array
    {
        $path = "/Project/{$projectRequest->id}";
        $project = $this->getProject($projectRequest->id);

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
