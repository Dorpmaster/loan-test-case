<?php

declare(strict_types=1);

namespace App\Domain\Client\Command;

use App\Domain\Client\ClientInterface;
use App\Dto\Client\ClientDto;

interface CreateClientFromDtoCommandInterface
{
    public function create(ClientDto $dto): ClientInterface;
}
