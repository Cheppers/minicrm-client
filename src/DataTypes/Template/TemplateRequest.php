<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\DataTypes\Template;

use Cheppers\MiniCrm\DataTypes\RequestBase;

class TemplateRequest extends RequestBase
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var int
     */
    public $categoryId;

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        $data = [];

        foreach (array_keys(get_object_vars($this)) as $key) {
            switch ($key) {
                case 'id':
                    $data['Id'] = $this->id;
                    break;
                case 'categoryId':
                    $data['CategoryId'] = $this->categoryId;
                    break;
            }
        }

        return $data;
    }
}
