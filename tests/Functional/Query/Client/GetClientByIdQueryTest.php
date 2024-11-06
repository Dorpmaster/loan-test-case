<?php

declare(strict_types=1);

namespace App\Tests\Functional\Query\Client;

use App\Domain\Client\Exception\ClientDoesNotExistException;
use App\Domain\Client\Query\GetClientByIdQueryInterface;
use App\Tests\Factory\ClientFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class GetClientByIdQueryTest extends KernelTestCase
{
    use ResetDatabase;
    use Factories;

    public function testQuery(): void
    {
        $clientEntity = ClientFactory::new()
            ->create()
            ->_real();

        $id = $clientEntity->getId();

        $query = self::getContainer()->get(GetClientByIdQueryInterface::class);

        self::assertEquals($clientEntity, $query->query($id));
    }

    public function testNotExist(): void
    {
        $id = Uuid::v7();

        $query = self::getContainer()->get(GetClientByIdQueryInterface::class);

        self::expectException(ClientDoesNotExistException::class);
        $query->query($id);
    }
}
