<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\Client;

use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Zenstruck\Browser\Json;
use Zenstruck\Browser\KernelBrowser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class CreateClientValidationTest extends WebTestCase
{
    use HasBrowser;
    use ResetDatabase;
    use Factories;

    private KernelBrowser $client;
    private UrlGeneratorInterface $urlGenerator;

    #[DataProvider('dataProvider')]
    public function testValidation(array $payload, array $subset): void
    {
        $this->client
            ->post(
                $this->urlGenerator->generate('create_client'),
                [
                    'json' => $payload,
                ],
            )
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->use(static function (Json $json) use ($subset) {
                $json->assertThat('violations', fn(Json $json) => $json->hasSubset($subset));
            });
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->client       = $this->browser();
        $this->urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
    }

    public static function dataProvider(): array
    {
        return [
            'Missing lastName' => [
                'payload' => [
                    'firstName' => 'John',
                    'age' => 30,
                    'address' => [
                        'city' => 'New-York',
                        'state' => 'NY',
                        'zip' => 54321
                    ],
                    'ssn' => '813-73-1610',
                    'fico' => 700,
                    'email' => 'user@example.com',
                    'phone' => '+11234567890',
                ],
                'subset' => [
                    [
                        'propertyPath' => 'lastName',
                        'title' => 'This value should be of type string.',
                    ]
                ]
            ],
            'Empty lastName' => [
                'payload' => [
                    'lastName' => '',
                    'firstName' => 'John',
                    'age' => 30,
                    'address' => [
                        'city' => 'New-York',
                        'state' => 'NY',
                        'zip' => 54321
                    ],
                    'ssn' => '813-73-1610',
                    'fico' => 700,
                    'email' => 'user@example.com',
                    'phone' => '+11234567890',
                ],
                'subset' => [
                    [
                        'propertyPath' => 'lastName',
                        'title' => 'This value should not be blank.',
                    ]
                ]
            ],
            'Too long lastName' => [
                'payload' => [
                    'lastName' => str_repeat('Doe', 100),
                    'firstName' => 'John',
                    'age' => 30,
                    'address' => [
                        'city' => 'New-York',
                        'state' => 'NY',
                        'zip' => 54321
                    ],
                    'ssn' => '813-73-1610',
                    'fico' => 700,
                    'email' => 'user@example.com',
                    'phone' => '+11234567890',
                ],
                'subset' => [
                    [
                        'propertyPath' => 'lastName',
                        'title' => 'This value is too long. It should have 100 characters or less.',
                    ]
                ]
            ]
        ];
    }
}
