<?php

declare(strict_types=1);

namespace App\Tests\Functional\Service\Client;

use App\Domain\Client\Service\EligibilityCheckerInterface;
use App\Entity\Address;
use App\Tests\Factory\ClientFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Zenstruck\Foundry\Test\Factories;

final class EligibilityCheckerTest extends KernelTestCase
{
    use Factories;

    private EligibilityCheckerInterface $checker;

    public function testMonthlyIncomeRule(): void
    {
        $address = (new Address())
            ->setCity('Los-Angeles')
            ->setState('CA')
            ->setZip(123445);

        $client = ClientFactory::new()
            ->withoutPersisting()
            ->create([
                'firstName' => 'Alex',
                'address' => $address,
                'fico' => 700,
                'age' => 30,
            ])
            ->_real();

        $violations = $this->checker->check($client);

        self::assertCount(0, $violations);

        $client = ClientFactory::new()
            ->withoutPersisting()
            ->create([
                'firstName' => 'John',
                'address' => $address,
                'fico' => 700,
                'age' => 30,
            ])
            ->_real();

        $violations = $this->checker->check($client);

        self::assertCount(1, $violations);

        $list      = iterator_to_array($violations);
        $violation = $list[0];
        self::assertInstanceOf(ConstraintViolationInterface::class, $violation);
        self::assertSame('Monthly income must be greater than 1000$', $violation->getMessage());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->checker = self::getContainer()->get(EligibilityCheckerInterface::class);
    }
}
