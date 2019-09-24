<?php
declare(strict_types = 1);

namespace Cheppers\MiniCrm\Tests\Unit\Endpoints;

use Cheppers\MiniCrm\DataTypes\Category\CategoryRequest;
use Cheppers\MiniCrm\DataTypes\Template\SimpleTemplateItem;
use Cheppers\MiniCrm\DataTypes\Template\TemplateRequest;
use Cheppers\MiniCrm\DataTypes\Template\TemplateResponse;
use Cheppers\MiniCrm\Endpoints\CategoryEndpoint;
use Cheppers\MiniCrm\Endpoints\TemplateEndpoint;
use Cheppers\MiniCrm\MiniCrmClient;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

/**
 * @group MiniCrmClient
 *
 * @covers \Cheppers\MiniCrm\Endpoints\TemplateEndpoint
 */
class TemplateEndpointTest extends TestCase
{

    /**
     * @var MiniCrmClient
     */
    protected $client;

    protected $clientOptions = [
        'baseUri' => 'http://minicrm.hu',
        'apiKey' => 'm-i-n-i',
        'systemId' => 1234
    ];
    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $client = new Client();
        $logger = new NullLogger();
        $this->client = new MiniCrmClient($client, $logger);
    }

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
        $container = [];
        $history = Middleware::history($container);
        $mock = new MockHandler([
            new Response(
                200,
                ['Content-Type' => 'application/json; charset=utf-8'],
                \GuzzleHttp\json_encode($response)
            ),
            new RequestException(
                'Error communicating with server.',
                new Request('GET', '/Api/R3/Template')
            )
        ]);
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);

        $client = new Client([
            'handler' => $handlerStack,
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
        $container = [];
        $history = Middleware::history($container);
        $mock = new MockHandler([
            new Response(
                200,
                ['Content-Type' => 'application/json; charset=utf-8'],
                \GuzzleHttp\json_encode($response)
            ),
            new RequestException(
                'Error communicating with server.',
                new Request('GET', '/Api/R3/Template')
            )
        ]);
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);

        $client = new Client([
            'handler' => $handlerStack,
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
