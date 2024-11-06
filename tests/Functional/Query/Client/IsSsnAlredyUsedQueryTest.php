<?php

declare(strict_types=1);

namespace App\Tests\Functional\Query\Client;

use App\Domain\Client\Query\IsSsnAlreadyUsedQueryInterface;
use App\Tests\Factory\ClientFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class IsSsnAlredyUsedQueryTest extends KernelTestCase
{
    use ResetDatabase;
    use Factories;

    public function testExist(): void
    {
        $clientEntity = ClientFactory::new()
            ->create(['ssn' => '123-12-1234'])
            ->_real();

        $ssn = $clientEntity->getSsn();

        $query = self::getContainer()->get(IsSsnAlreadyUsedQueryInterface::class);

        self::assertTrue($query->query($ssn));
    }

    public function testNotExist(): void
    {
        ClientFactory::new()
            ->create()
            ->_real();

        $ssn = '123-12-1234';

        $query = self::getContainer()->get(IsSsnAlreadyUsedQueryInterface::class);

        self::assertFalse($query->query($ssn));
    }
}
