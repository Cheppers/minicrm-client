<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Endpoints;

use Cheppers\MiniCrm\DataTypes\Todo\SingleTodoResponse;
use Cheppers\MiniCrm\DataTypes\Todo\TodoListResponse;
use Cheppers\MiniCrm\DataTypes\Todo\TodoRequest;
use Cheppers\MiniCrm\MiniCrmClient;

class TodoEndpoint extends MiniCrmClient
{
    /**
     * @param int $todoId
     *
     * @return \Cheppers\MiniCrm\DataTypes\Todo\SingleTodoResponse
     *
     * @throws \Exception
     */
    public function getTodo(int $todoId): SingleTodoResponse
    {
        $path = "/ToDo/{$todoId}";

        $response = $this->sendRequest(
            'GET',
            TodoRequest::__set_state([]),
            $path
        );

        $body = $this->validateAndParseResponse($response);
        $project = SingleTodoResponse::__set_state($body);

        return $project;
    }

    /**
     * @param int $projectId
     *
     * @return \Cheppers\MiniCrm\DataTypes\Todo\TodoListResponse
     *
     * @throws \Exception
     */
    public function getTodoList(int $projectId): TodoListResponse
    {
        $path = "/ToDoList/{$projectId}";

        $response = $this->sendRequest(
            'GET',
            TodoRequest::__set_state([]),
            $path
        );

        $body = $this->validateAndParseResponse($response);
        $project = TodoListResponse::__set_state($body);

        return $project;
    }

    /**
     * @param \Cheppers\MiniCrm\DataTypes\Todo\TodoRequest $todoRequest
     *
     * @return array
     *
     * @throws \Exception
     */
    public function createTodo(TodoRequest $todoRequest): array
    {
        $response = $this->sendRequest('PUT', $todoRequest, '/ToDo');
        $body = $this->validateAndParseResponse($response);

        return $body;
    }

    /**
     * Updates a Todo. 'TodoId' and 'ProjetId' are mandatory.
     * Only Todos that are in 'Open' status can be updated.
     * ProjectId of an existing Todo cannot be changed.
     *
     * @param \Cheppers\MiniCrm\DataTypes\Todo\TodoRequest $todoRequest
     *
     * @return array
     *
     * @throws \Exception
     */
    public function updateTodo(TodoRequest $todoRequest): array
    {
        $path = "/ToDo/{$todoRequest->id}";
        $todo = $this->getTodo($todoRequest->id);

        foreach ($todoRequest as $key => $item) {
            if (!empty($item)) {
                $todo->{$key} = $item;
            }
        }
        // No need, also can't send it as data.
        unset($todo->id);

        foreach ($todo as $key => $item) {
            $todoHelper[$key] = $item;
        }
        $updatedTodoRequest = TodoRequest::__set_state($todoHelper);

        $response = $this->sendRequest('PUT', $updatedTodoRequest, $path);
        $body = $this->validateAndParseResponse($response);

        return $body;
    }
}
