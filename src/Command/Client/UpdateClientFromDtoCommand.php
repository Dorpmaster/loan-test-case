<?php

declare(strict_types=1);

namespace App\Command\Client;

use App\Domain\Client\ClientInterface;
use App\Domain\Client\Command\UpdateClientFromDtoCommandInterface;
use App\Domain\Client\Repository\ClientRepositoryInterface;
use App\Dto\Client\UpdateClientDto;
use App\Entity\Address;

final readonly class UpdateClientFromDtoCommand implements UpdateClientFromDtoCommandInterface
{
    public function __construct(
        private ClientRepositoryInterface $repository,
    ) {
    }

    public function update(UpdateClientDto $dto, ClientInterface $client): void
    {
        if ($dto->lastName !== null) {
            $client->setLastName($dto->lastName);
        }

        if ($dto->firstName !== null) {
            $client->setFirstName($dto->firstName);
        }

        if ($dto->age !== null) {
            $client->setAge($dto->age);
        }

        if ($dto->address !== null) {
            $addressDto = $dto->address;
            $address    = (new Address())
                ->setCity($addressDto->city)
                ->setState($addressDto->state)
                ->setZip($addressDto->zip);

            $client->setAddress($address);
        }

        if ($dto->fico !== null) {
            $client->setFico($dto->fico);
        }

        if ($dto->ssn !== null) {
            $client->setSsn($dto->ssn);
        }

        if ($dto->email !== null) {
            $client->setEmail($dto->email);
        }

        if ($dto->phone !== null) {
            $client->setPhone($dto->phone);
        }

        $this->repository->save($client);
    }
}
