<?php

declare(strict_types=1);

namespace App\Query\Client;

use App\Domain\Client\ClientInterface;
use App\Domain\Client\Query\GetClientByIdQueryInterface;
use App\Domain\Client\Repository\ClientRepositoryInterface;
use Symfony\Component\Uid\Uuid;

final readonly class GetClientByIdQuery implements GetClientByIdQueryInterface
{
    public function __construct(
        private ClientRepositoryInterface $repository,
    ) {
    }

    public function query(Uuid $id): ClientInterface
    {
        return $this->repository->getById($id);
    }
}
