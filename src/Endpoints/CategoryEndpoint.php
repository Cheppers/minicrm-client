<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Endpoints;

use Cheppers\MiniCrm\DataTypes\Category\Categories;
use Cheppers\MiniCrm\DataTypes\Category\CategoriesDetailed;
use Cheppers\MiniCrm\MiniCrmClient;

class CategoryEndpoint extends MiniCrmClient
{

    /**
     * @return \Cheppers\MiniCrm\DataTypes\Category\Categories
     */
    public function getCategories(): Categories
    {
        $response = $this->get('/Category');

        $body = $this->validateAndParseResponse($response);
        $categories = Categories::__set_state($body);

        return $categories;
    }

    /**
     * @return \Cheppers\MiniCrm\DataTypes\Category\CategoriesDetailed
     */
    public function getCategoriesDetailed(): CategoriesDetailed
    {
        $response = $this->get('/Category?Detailed=1');

        $body = $this->validateAndParseResponse($response);
        $categories = CategoriesDetailed::__set_state($body);

        return $categories;
    }

    /**
     * @param string $name
     *
     * @return int|null
     */
    public function getCategoryId(string $name)
    {
        $response = $this->get('/Category');

        $body = $this->validateAndParseResponse($response);
        $categoryId = array_search($name, $body, true);

        if (!$categoryId) {
            $this
                ->logger
                ->error(
                    sprintf(
                        "There are no categories with the name '%s'",
                        $name
                    )
                );

            return null;
        }

        return $categoryId;
    }
}
