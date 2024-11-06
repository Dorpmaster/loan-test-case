<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\Client;

use App\Tests\Factory\ClientFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Zenstruck\Browser\KernelBrowser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class UpdateClientSsnValidationTest extends WebTestCase
{
    use HasBrowser;
    use ResetDatabase;
    use Factories;

    private KernelBrowser $client;
    private UrlGeneratorInterface $urlGenerator;

    public function testValidation(): void
    {
        $payload = [
            'ssn' => '813-73-1610',
        ];

        ClientFactory::createOne(['ssn' => $payload['ssn']]);

        $client = ClientFactory::createOne()
            ->_real();

        $this->client
            ->patch(
                $this->urlGenerator->generate('update_client', ['id' => $client->getId()]),
                [
                    'json' => $payload,
                ],
            )
            ->assertStatus(Response::HTTP_CONFLICT)
        ;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->client       = $this->browser();
        $this->urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
    }
}
