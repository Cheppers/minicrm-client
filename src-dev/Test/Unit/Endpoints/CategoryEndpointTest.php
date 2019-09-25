<?php
declare(strict_types = 1);

namespace Cheppers\MiniCrm\Test\Unit\Endpoints;

use Cheppers\MiniCrm\DataTypes\Category\CategoryRequest;
use Cheppers\MiniCrm\DataTypes\Category\CategoryResponse;
use Cheppers\MiniCrm\DataTypes\Category\DetailedCategoryResponse;
use Cheppers\MiniCrm\Endpoints\CategoryEndpoint;
use Cheppers\MiniCrm\MiniCrmClient;
use Cheppers\MiniCrm\Test\Unit\MiniCrmBaseTest;
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
 * @covers \Cheppers\MiniCrm\Endpoints\CategoryEndpoint
 */
class CategoryEndpointTest extends MiniCrmBaseTest
{
    public function casesCategories()
    {
        $categoryData = [
            '1' => 'Test Category 1',
            '2' => 'Test Category 2',
            '3' => 'Test Category 3',
            '4' => 'Test Category 4',
            '5' => 'Test Category 5',
        ];

        return [
            'category' => [
                $categoryData,
                $categoryData,
            ],
        ];
    }

    /**
     * @dataProvider casesCategories
     */
    public function testGetCategories(array $expected, array $response)
    {
        $client = new Client([
            'handler' => $this->createMiniCrmMock(
                $response,
                'GET',
                '/Api/R3/Category'
            ),
        ]);
        $logger = new NullLogger();
        $category = new CategoryEndpoint($client, $logger);
        $category->setCredentials($this->clientOptions);

        $body = CategoryRequest::__set_state($expected);
        $categories = $category->getCategories($body);

        if ($expected) {
            static::assertEquals(
                json_encode(CategoryResponse::__set_state($expected), JSON_PRETTY_PRINT),
                json_encode($categories, JSON_PRETTY_PRINT)
            );
        } else {
            static::assertNull($categories);
        }
    }

    public function casesDetailedCategories()
    {
        $categoryData = [
            '1' => [
                '1' => [
                    'id' => 1,
                    'orderId' => 1,
                    'name' => 'Test Category 1',
                    'type' => 'Test Category Type',
                    'senderName' => 'Test Category Sender Name',
                    'senderEmail' => 'test@categsender.mail',
                    'phone' => '123456789',
                ]
            ],
            '2' => [
                '2' => [
                    'id' => 2,
                    'orderId' => 2,
                    'name' => 'Test Category 2',
                    'type' => 'Test Category Type',
                    'senderName' => 'Test Category Sender Name',
                    'senderEmail' => 'test@categsender.mail',
                    'phone' => '123456789',
                ]
            ],
        ];

        return [
            'category' => [
                $categoryData,
                $categoryData,
            ],
        ];
    }

    /**
     * @dataProvider casesDetailedCategories
     */
    public function testGetDetailedCategories(array $expected, array $response)
    {
        $client = new Client([
            'handler' => $this->createMiniCrmMock(
                $response,
                'GET',
                '/Api/R3/Category'
            ),
        ]);
        $logger = new NullLogger();
        $category = new CategoryEndpoint($client, $logger);
        $category->setCredentials($this->clientOptions);

        $body = CategoryRequest::__set_state($expected);
        $categories = $category->getCategories($body, true);

        if ($expected) {
            static::assertEquals(
                json_encode(DetailedCategoryResponse::__set_state($expected), JSON_PRETTY_PRINT),
                json_encode($categories, JSON_PRETTY_PRINT)
            );
        } else {
            static::assertNull($categories);
        }
    }
}
