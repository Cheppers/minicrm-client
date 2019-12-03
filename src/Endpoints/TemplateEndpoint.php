<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Endpoints;

use Cheppers\MiniCrm\DataTypes\Template\SimpleTemplateItem;
use Cheppers\MiniCrm\DataTypes\Template\TemplateRequest;
use Cheppers\MiniCrm\DataTypes\Template\TemplateResponse;
use Cheppers\MiniCrm\MiniCrmClient;

class TemplateEndpoint extends MiniCrmClient
{
    /**
     * @param int $templateId
     *
     * @return \Cheppers\MiniCrm\DataTypes\Template\SimpleTemplateItem
     *
     * @throws \Exception
     */
    public function get(int $templateId): SimpleTemplateItem
    {
        $path = "/Template/{$templateId}";

        $response = $this->sendRequest(
            'GET',
            TemplateRequest::__set_state([]),
            $path
        );

        $body = $this->validateAndParseResponse($response);

        return SimpleTemplateItem::__set_state($body);
    }

    /**
     * @param \Cheppers\MiniCrm\DataTypes\Template\TemplateRequest $templateRequest
     *
     * @return \Cheppers\MiniCrm\DataTypes\Template\TemplateResponse
     *
     * @throws \Exception
     */
    public function getList(TemplateRequest $templateRequest): TemplateResponse
    {
        $response = $this->sendRequest(
            'GET',
            $templateRequest,
            "/TemplateList/{$templateRequest->categoryId}"
        );

        $body = $this->validateAndParseResponse($response);

        return TemplateResponse::__set_state($body);
    }
}
