<?php

declare(strict_types=1);

namespace App\Domain\Client\Repository;

use App\Domain\Client\Exception\ClientDoesNotExistException;
use App\Entity\Client;
use Symfony\Component\Uid\Uuid;

interface ClientRepositoryInterface
{
    /**
     * @throws ClientDoesNotExistException
     */
    public function getById(Uuid $id): Client;

    public function isSsnExist(string $ssn): bool;

    public function save(Client $client): void;
}
