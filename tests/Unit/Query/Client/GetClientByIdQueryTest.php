<?php

declare(strict_types=1);

namespace App\Tests\Unit\Query\Client;

use App\Domain\Client\Exception\ClientDoesNotExistException;
use App\Domain\Client\Repository\ClientRepositoryInterface;
use App\Query\Client\GetClientByIdQuery;
use App\Tests\Factory\ClientFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;
use Zenstruck\Foundry\Test\Factories;

final class GetClientByIdQueryTest extends TestCase
{
    use Factories;

    public function testQuery(): void
    {
        $clientEntity = ClientFactory::new()
            ->withoutPersisting()
            ->create()
            ->_real();

        $id = $clientEntity->getId();

        $repository = self::createMock(ClientRepositoryInterface::class);
        $repository->method('getById')
            ->with($id)
            ->willReturn($clientEntity);

        $query = new GetClientByIdQuery($repository);

        self::assertEquals($clientEntity, $query->query($id));
    }

    public function testNotExist(): void
    {
        $id = Uuid::v7();

        $repository = self::createMock(ClientRepositoryInterface::class);
        $repository->method('getById')
            ->with($id)
            ->willThrowException(new ClientDoesNotExistException());

        $query = new GetClientByIdQuery($repository);

        self::expectException(ClientDoesNotExistException::class);
        $query->query($id);
    }
}
