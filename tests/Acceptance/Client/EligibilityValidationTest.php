<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\Client;

use App\Entity\Address;
use App\Tests\Factory\ClientFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Zenstruck\Browser\Json;
use Zenstruck\Browser\KernelBrowser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class EligibilityValidationTest extends WebTestCase
{
    use HasBrowser;
    use ResetDatabase;
    use Factories;

    private KernelBrowser $client;
    private UrlGeneratorInterface $urlGenerator;

    public function testMonthlyIncomeRule(): void
    {
        $address = (new Address())
            ->setCity('Los-Angeles')
            ->setState('CA')
            ->setZip(123445);

        $client = ClientFactory::createOne([
            'firstName' => 'John',
            'address' => $address,
            'fico' => 700,
            'age' => 30,
        ])->_real();

        $this->client
            ->post(
                $this->urlGenerator->generate('check_eligibility', ['id' => $client->getId()]),
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ]
                ],
            )
            ->assertStatus(Response::HTTP_CONFLICT)
            ->use(static function (Json $json) {
                $json->assertThat('violations', fn(Json $json) => $json->hasSubset([
                    [
                        'title' => 'Monthly income must be greater than 1000$',
                    ]
                ]));
            });
        ;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->client       = $this->browser();
        $this->urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
    }
}
