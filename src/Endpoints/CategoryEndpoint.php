<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Endpoints;

use Cheppers\MiniCrm\DataTypes\Category\CategoryRequest;
use Cheppers\MiniCrm\DataTypes\Category\CategoryResponse;
use Cheppers\MiniCrm\DataTypes\Category\CategoryDetailedResponse;
use Cheppers\MiniCrm\MiniCrmClient;

class CategoryEndpoint extends MiniCrmClient
{

    /**
     * @return \Cheppers\MiniCrm\DataTypes\Category\CategoryResponse
     *
     * @throws \Exception
     */
    public function getCategories(): CategoryResponse
    {
        $response = $this->sendRequest('GET', CategoryRequest::__set_state([]), '/Category');

        $body = $this->validateAndParseResponse($response);
        $categories = CategoryResponse::__set_state($body);

        return $categories;
    }

    /**
     * @return \Cheppers\MiniCrm\DataTypes\Category\CategoryDetailedResponse
     *
     * @throws \Exception
     */
    public function getCategoriesDetailed(): CategoryDetailedResponse
    {
        $response = $this->sendRequest('GET', CategoryRequest::__set_state([]), '/Category?Detailed=1');

        $body = $this->validateAndParseResponse($response);
        $categories = CategoryDetailedResponse::__set_state($body);

        return $categories;
    }

    /**
     * @param string $name
     *
     * @return false|int|string|null
     *
     * @throws \Exception
     */
    public function getCategoryId(string $name)
    {
        $response = $this->sendRequest('GET', CategoryRequest::__set_state([]), '/Category');

        $body = $this->validateAndParseResponse($response);
        $categoryId = array_search($name, $body, true);

        if (!$categoryId) {
            return null;
        }

        return $categoryId;
    }
}
