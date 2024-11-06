<?php

declare(strict_types=1);

namespace App\Domain\Client\Query;

use App\Domain\Client\ClientInterface;
use App\Domain\Client\Exception\ClientDoesNotExistException;
use Symfony\Component\Uid\Uuid;

interface GetClientByIdQueryInterface
{
    /**
     * @throws ClientDoesNotExistException
     */
    public function query(Uuid $id): ClientInterface;
}
