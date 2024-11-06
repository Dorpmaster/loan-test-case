<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\Client;

use App\Tests\Factory\ClientFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Zenstruck\Browser\Json;
use Zenstruck\Browser\KernelBrowser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class CreateClientSsnValidationTest extends WebTestCase
{
    use HasBrowser;
    use ResetDatabase;
    use Factories;

    private KernelBrowser $client;
    private UrlGeneratorInterface $urlGenerator;

    public function testValidation(): void
    {
        $payload = [
            'lastName' => 'Doe',
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
        ];

        $subset = [
            [
                'propertyPath' => 'ssn',
                'title' => 'Client with such SSN already exist.',
            ]
        ];

        ClientFactory::createOne(['ssn' => $payload['ssn']]);

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
}
