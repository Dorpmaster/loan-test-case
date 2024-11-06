<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\Client;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Uid\Uuid;
use Zenstruck\Browser\Json;
use Zenstruck\Browser\KernelBrowser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class CreateClientSuccessfulTest extends WebTestCase
{
    use HasBrowser;
    use ResetDatabase;
    use Factories;

    private KernelBrowser $client;
    private UrlGeneratorInterface $urlGenerator;

    public function testCreate(): void
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

        $this->client
            ->post(
                $this->urlGenerator->generate('create_client'),
                [
                    'json' => $payload,
                ],
            )
            ->assertSuccessful()
            ->assertStatus(Response::HTTP_CREATED)
            ->use(static function (Json $json) use ($payload) {
                $json->assertHas('id');
                Uuid::isValid($json->decoded()['id']);

                $json->hasSubset($payload);
            });
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->client       = $this->browser();
        $this->urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
    }
}
