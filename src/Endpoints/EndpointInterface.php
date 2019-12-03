<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Endpoints;

use Cheppers\MiniCrm\DataTypes\RequestBase;

interface EndpointInterface
{
    public function get(int $id);

    public function create(RequestBase $request): array;

    public function update(RequestBase $request): array;
}
