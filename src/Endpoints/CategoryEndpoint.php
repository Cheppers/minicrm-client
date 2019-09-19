<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Endpoints;

use Cheppers\MiniCrm\DataTypes\Category\CategoryRequest;
use Cheppers\MiniCrm\DataTypes\Category\CategoryResponse;
use Cheppers\MiniCrm\DataTypes\Category\DetailedCategoryResponse;
use Cheppers\MiniCrm\MiniCrmClient;

class CategoryEndpoint extends MiniCrmClient
{
    /**
     * @param \Cheppers\MiniCrm\DataTypes\Category\CategoryRequest $categoryRequest
     * @param bool $detailed
     *
     * @return \Cheppers\MiniCrm\DataTypes\Category\CategoryResponse|\Cheppers\MiniCrm\DataTypes\Category\DetailedCategoryResponse
     *
     * @throws \Exception
     */
    public function getCategories(CategoryRequest $categoryRequest, bool $detailed = false)
    {
        $path = '/Category';
        $path = $detailed ? "{$path}?Detailed=1" : $path;

        $response = $this->sendRequest(
            'GET',
            $categoryRequest,
            $path
        );

        $body = $this->validateAndParseResponse($response);
        $categories = $detailed
            ? DetailedCategoryResponse::__set_state($body)
            : CategoryResponse::__set_state($body);

        return $categories;
    }

    /**
     * @param string $name
     *
     * @return false|int|string|null
     *
     * @throws \Exception
     */
    public function getCategoryIdByName(string $name)
    {
        $response = $this->sendRequest(
            'GET',
            CategoryRequest::__set_state([]),
            '/Category'
        );

        $body = $this->validateAndParseResponse($response);
        $categoryId = array_search($name, $body, true);

        if (!$categoryId) {
            return null;
        }

        return $categoryId;
    }
}
