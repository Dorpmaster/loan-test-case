<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\Client;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Uid\Uuid;
use Zenstruck\Browser\KernelBrowser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class UpdateClientNotFoundTest extends WebTestCase
{
    use HasBrowser;
    use ResetDatabase;
    use Factories;

    private KernelBrowser $client;
    private UrlGeneratorInterface $urlGenerator;

    public function testValidation(): void
    {
        $payload = [
            'ssn' => '123-12-1234',
        ];

        $this->client
            ->patch(
                $this->urlGenerator->generate('update_client', ['id' => Uuid::v7()]),
                [
                    'json' => $payload,
                ],
            )
            ->assertStatus(Response::HTTP_NOT_FOUND)
        ;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->client       = $this->browser();
        $this->urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
    }
}
