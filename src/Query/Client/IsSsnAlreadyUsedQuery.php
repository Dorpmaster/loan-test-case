<?php

declare(strict_types=1);

namespace App\Query\Client;

use App\Domain\Client\Query\IsSsnAlreadyUsedQueryInterface;
use App\Domain\Client\Repository\ClientRepositoryInterface;

final readonly class IsSsnAlreadyUsedQuery implements IsSsnAlreadyUsedQueryInterface
{
    public function __construct(
        private ClientRepositoryInterface $repository,
    ) {
    }

    public function query(string $ssn): bool
    {
        return $this->repository->isSsnExist($ssn);
    }
}
