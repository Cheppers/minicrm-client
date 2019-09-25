<?php
declare(strict_types = 1);

namespace Cheppers\MiniCrm\Test\Unit\Endpoints;

use Cheppers\MiniCrm\DataTypes\Template\SimpleTemplateItem;
use Cheppers\MiniCrm\DataTypes\Template\TemplateRequest;
use Cheppers\MiniCrm\DataTypes\Template\TemplateResponse;
use Cheppers\MiniCrm\Endpoints\TemplateEndpoint;
use Cheppers\MiniCrm\Test\Unit\MiniCrmBaseTest;
use GuzzleHttp\Client;
use Psr\Log\NullLogger;

/**
 * @group MiniCrmClient
 *
 * @covers \Cheppers\MiniCrm\Endpoints\TemplateEndpoint
 */
class TemplateEndpointTest extends MiniCrmBaseTest
{
    public function casesTemplate()
    {
        $templateData = [
            'id' => 1,
            'type' => 'Test Template Type',
            'name' => 'Test Template Name',
            'url' => 'test@template.url',
            'subject' => 'Test Template Subject',
            'folderId' => 1,
            'content' => 'Test Template content',
        ];

        return [
            'template' => [
                $templateData,
                $templateData,
            ],
        ];
    }

    /**
     * @dataProvider casesTemplate
     */
    public function testGetTemplate(array $expected, array $response)
    {
        $client = new Client([
            'handler' => $this->createMiniCrmMock(
                $response,
                'GET',
                '/Api/R3/Template'
            ),
        ]);
        $logger = new NullLogger();
        $template = new TemplateEndpoint($client, $logger);
        $template->setCredentials($this->clientOptions);

        $tmpl = $template->getTemplate($expected['id']);

        if ($expected) {
            static::assertEquals(
                json_encode(SimpleTemplateItem::__set_state($expected), JSON_PRETTY_PRINT),
                json_encode($tmpl, JSON_PRETTY_PRINT)
            );
        } else {
            static::assertNull($tmpl);
        }
    }

    public function casesTemplateList()
    {
        $templateData = [
            'id' => 1,
            'type' => 'Test Template Type',
            'name' => 'Test Template Name',
            'url' => 'test@template.url',
            'subject' => 'Test Template Subject',
            'folderId' => 1,
            'content' => 'Test Template content',
            'categoryId' => 1,
        ];

        return [
            'template' => [
                [
                    'Results' => $templateData,
                    'Count' => 1,
                ],
                [
                    'Results' => $templateData,
                    'Count' => 1,
                ],
            ],
        ];
    }

    /**
     * @dataProvider casesTemplateList
     */
    public function testGetTemplateList(array $expected, array $response)
    {
        $client = new Client([
            'handler' => $this->createMiniCrmMock(
                $response,
                'GET',
                '/Api/R3/Template'
            ),
        ]);
        $logger = new NullLogger();
        $template = new TemplateEndpoint($client, $logger);
        $template->setCredentials($this->clientOptions);

        $tmpl = $template->getTemplateList(TemplateRequest::__set_state([
            'categoryId' => $expected['Results']['categoryId'],
        ]));

        if ($expected) {
            static::assertEquals(
                json_encode(TemplateResponse::__set_state($expected), JSON_PRETTY_PRINT),
                json_encode($tmpl, JSON_PRETTY_PRINT)
            );
        } else {
            static::assertNull($tmpl);
        }
    }
}
