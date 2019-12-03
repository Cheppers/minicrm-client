<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Test\Unit\Endpoints;

use Cheppers\MiniCrm\DataTypes\Template\SimpleTemplateItem;
use Cheppers\MiniCrm\DataTypes\Template\TemplateItem;
use Cheppers\MiniCrm\DataTypes\Template\TemplateRequest;
use Cheppers\MiniCrm\DataTypes\Template\TemplateResponse;
use Cheppers\MiniCrm\Endpoints\TemplateEndpoint;
use Cheppers\MiniCrm\Test\Unit\MiniCrmBaseTest;
use GuzzleHttp\Psr7\Response;
use Psr\Log\NullLogger;

/**
 * @group Endpoints
 *
 * @covers \Cheppers\MiniCrm\Endpoints\TemplateEndpoint
 */
class TemplateEndpointTest extends MiniCrmBaseTest
{
    public function casesTemplate()
    {
        return [
            'empty' => [
                SimpleTemplateItem::__set_state([]),
                [],
                1
            ],
            'basic' => [
                SimpleTemplateItem::__set_state([
                    'Id' => 42,
                    'Type' => 'Open',
                    'Name' => 42,
                    'Url' => 'Test Template 42',
                    'Subject' => '2019-12-12 12:12:12',
                    'FolderId' => 'Test User ID 42',
                    'Content' => 'Test Template Item Content',
                ]),
                [
                    'Id' => 42,
                    'Type' => 'Open',
                    'Name' => 42,
                    'Url' => 'Test Template 42',
                    'Subject' => '2019-12-12 12:12:12',
                    'FolderId' => 'Test User ID 42',
                    'Content' => 'Test Template Item Content',
                ],
                42
            ],
        ];
    }

    /**
     * @param $expected
     * @param array $responseBody
     * @param int $templateId
     *
     * @throws \Exception
     *
     * @dataProvider casesTemplate
     */
    public function testGetTemplate(
        $expected,
        array $responseBody,
        int $templateId
    ) {
        $mock = $this->createMiniCrmMock([
            new Response(
                200,
                ['Content-Type' => 'application/json; charset=utf-8'],
                \GuzzleHttp\json_encode($responseBody)
            ),
        ]);
        $client = $mock['client'];
        $templateEndpoint = new TemplateEndpoint($client, new NullLogger());
        $templateEndpoint->setCredentials($this->clientOptions);

        $template = $templateEndpoint->get($templateId);

        static::assertEquals(
            json_encode($expected, JSON_PRETTY_PRINT),
            json_encode($template, JSON_PRETTY_PRINT)
        );
    }

    /**
     * @return array
     */
    public function casesTemplateList()
    {
        return [
            'empty' => [
                TemplateResponse::__set_state([]),
                [
                    'Results' => null,
                    'Count' => null,
                ],
                TemplateRequest::__set_state([]),
            ],
            'basic' => [
                TemplateResponse::__set_state([
                    'Results' => [
                        0 => TemplateItem::__set_state([
                            'Id' => 1,
                            'Type' => 1,
                            'Name' => 1,
                            'Url' => 1,
                        ])
                    ],
                    'Count' => 1
                ]),
                [
                    'Results' => [
                        0 => [
                            'Id' => 1,
                            'Type' => 1,
                            'Name' => 1,
                            'Url' => 1,
                        ],
                    ],
                    'Count' => 1
                ],
                TemplateRequest::__set_state([
                    'categoryId' => 19,
                ])
            ],
        ];
    }

    /**
     * @param $expected
     * @param array $responseBody
     * @param $request
     *
     * @throws \Exception
     *
     * @dataProvider casesTemplateList
     */
    public function testGetTemplateList(
        $expected,
        array $responseBody,
        $request
    ) {
        $mock = $this->createMiniCrmMock([
            new Response(
                200,
                ['Content-Type' => 'application/json; charset=utf-8'],
                \GuzzleHttp\json_encode($responseBody)
            ),
        ]);
        $client = $mock['client'];
        $templateEndpoint = new TemplateEndpoint($client, new NullLogger());
        $templateEndpoint->setCredentials($this->clientOptions);

        $template = $templateEndpoint->getList($request);

        static::assertEquals(
            json_encode($expected, JSON_PRETTY_PRINT),
            json_encode($template, JSON_PRETTY_PRINT)
        );
    }
}
