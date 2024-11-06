<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\Client;

use App\Entity\Address;
use App\Tests\Factory\ClientFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Uid\Uuid;
use Zenstruck\Browser\Json;
use Zenstruck\Browser\KernelBrowser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class IssueSuccessfulTest extends WebTestCase
{
    use HasBrowser;
    use ResetDatabase;
    use Factories;

    private KernelBrowser $client;
    private UrlGeneratorInterface $urlGenerator;

    public function testIssue(): void
    {
        $address = (new Address())
            ->setCity('Los-Angeles')
            ->setState('CA')
            ->setZip(123445);

        $client = ClientFactory::createOne([
            'firstName' => 'Alex',
            'address' => $address,
            'fico' => 700,
            'age' => 30,
        ])->_real();

        $this->client
            ->post(
                $this->urlGenerator->generate('issue_loan', ['id' => $client->getId()]),
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ],
                    'json' => [
                        'propositionId' => Uuid::v7(),
                    ],
                ],
            )
            ->assertStatus(Response::HTTP_CREATED)
            ->use(static function (Json $json) {
                $json->assertHas('id');
                Uuid::isValid($json->decoded()['id']);

                $json->hasSubset([
                    'productName' => 'Loan',
                    'term' => 60,
                    'interestRate' => 24.5,
                    'amount' => 5000.0
                ]);
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
