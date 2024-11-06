<?php

declare(strict_types=1);

namespace App\Command\Client;

use App\Domain\Client\ClientInterface;
use App\Domain\Client\Command\CreateClientFromDtoCommandInterface;
use App\Domain\Client\Repository\ClientRepositoryInterface;
use App\Dto\Client\ClientDto;
use App\Entity\Address;
use App\Entity\Client;
use Symfony\Component\Uid\Uuid;

final readonly class CreateClientFromDtoCommand implements CreateClientFromDtoCommandInterface
{
    public function __construct(
        private ClientRepositoryInterface $repository,
    ) {
    }

    public function create(ClientDto $dto): ClientInterface
    {
        $addressDto = $dto->address;
        $address    = (new Address())
            ->setCity($addressDto->city)
            ->setState($addressDto->state)
            ->setZip($addressDto->zip);

        $client = (new Client())
            ->setId(Uuid::v7())
            ->setLastName($dto->lastName)
            ->setFirstName($dto->firstName)
            ->setAge($dto->age)
            ->setAddress($address)
            ->setFico($dto->fico)
            ->setSsn($dto->ssn)
            ->setEmail($dto->email)
            ->setPhone($dto->phone);

        $this->repository->save($client);

        return $client;
    }
}
