<?php

declare(strict_types=1);

namespace App\Tests\Unit\Query\Client;

use App\Domain\Client\Repository\ClientRepositoryInterface;
use App\Query\Client\IsSsnAlreadyUsedQuery;
use PHPUnit\Framework\TestCase;

final class IsSsnAlreadyUsedQueryTest extends TestCase
{
    public function testExist(): void
    {
        $ssn = '123-12-1234';

        $repository = self::createMock(ClientRepositoryInterface::class);
        $repository->method('isSsnExist')
            ->with($ssn)
            ->willReturn(true);

        $query = new IsSsnAlreadyUsedQuery($repository);

        self::assertTrue($query->query($ssn));
    }

    public function testNotExist(): void
    {
        $ssn = '123-12-1234';

        $repository = self::createMock(ClientRepositoryInterface::class);
        $repository->method('isSsnExist')
            ->with($ssn)
            ->willReturn(false);

        $query = new IsSsnAlreadyUsedQuery($repository);

        self::assertFalse($query->query($ssn));
    }
}
