<?php

declare(strict_types=1);

namespace App\Repository\Client;

use App\Domain\Client\Exception\ClientDoesNotExistException;
use App\Domain\Client\Repository\ClientRepositoryInterface;
use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Uid\Uuid;

final class ClientRepository extends EntityRepository implements ClientRepositoryInterface
{
    public function getById(Uuid $id): Client
    {
        $client = $this->find($id);

        if (($client instanceof Client) === false) {
            throw new ClientDoesNotExistException();
        }

        return $client;
    }

    public function isSsnExist(string $ssn): bool
    {
        $client = $this->findOneBy(['ssn' => $ssn]);

        return $client instanceof Client;
    }

    public function save(Client $client): void
    {
        $em = $this->getEntityManager();

        $em->persist($client);
        $em->flush();
    }


    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, $em->getClassMetadata(Client::class));
    }
}
