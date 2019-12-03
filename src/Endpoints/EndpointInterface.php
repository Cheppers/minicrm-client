<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Endpoints;

use Cheppers\MiniCrm\DataTypes\RequestBase;

interface EndpointInterface
{
    /**
     * @param int $id
     *
     * @return mixed
     */
    public function get(int $id);

    /**
     * @param RequestBase $request
     *
     * @return array
     */
    public function create(RequestBase $request): array;

    /**
     * @param RequestBase $request
     *
     * @return array
     */
    public function update(RequestBase $request): array;
}
