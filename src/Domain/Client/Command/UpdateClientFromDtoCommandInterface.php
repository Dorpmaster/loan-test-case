<?php

declare(strict_types=1);

namespace App\Domain\Client\Command;

use App\Domain\Client\ClientInterface;
use App\Dto\Client\UpdateClientDto;

interface UpdateClientFromDtoCommandInterface
{
    public function update(UpdateClientDto $dto, ClientInterface $client): void;
}
