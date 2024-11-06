<?php

declare(strict_types=1);

namespace App\Tests\Functional\Repository\Client;

use App\Domain\Client\Exception\ClientDoesNotExistException;
use App\Domain\Client\Repository\ClientRepositoryInterface;
use App\Entity\Address;
use App\Entity\Client;
use App\Tests\Factory\ClientFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class ClientRepositoryTest extends KernelTestCase
{
    use ResetDatabase;
    use Factories;

    public function testGetById(): void
    {
        $client = ClientFactory::new()
            ->createOne();

        $id = $client->getId();

        /** @var ClientRepositoryInterface $repository */
        $repository = self::getContainer()->get(ClientRepositoryInterface::class);

        self::assertEquals($client->_real(), $repository->getById($id));
    }

    public function testGetByIdNoEntity(): void
    {
        $id = Uuid::v7();

        /** @var ClientRepositoryInterface $repository */
        $repository = self::getContainer()->get(ClientRepositoryInterface::class);

        self::expectException(ClientDoesNotExistException::class);
        $repository->getById($id);
    }

    public function testIsSsnExist(): void
    {
        $client = ClientFactory::new()
            ->createOne();

        $ssn = $client->getSsn();

        /** @var ClientRepositoryInterface $repository */
        $repository = self::getContainer()->get(ClientRepositoryInterface::class);

        self::assertTrue($repository->isSsnExist($ssn));
        self::assertFalse($repository->isSsnExist('test'));
    }

    public function testSave(): void
    {
        $id = Uuid::v7();

        $entity = (new Client())
            ->setId($id)
            ->setLastName('Doe')
            ->setFirstName('John')
            ->setAge(18)
            ->setSsn('123-12-1234')
            ->setFico(500)
            ->setEmail('user@example.com')
            ->setPhone('+11234567890')
            ->setAddress((new Address())
                ->setCity('Boston')
                ->setState('AL')
                ->setZip(12345));

        /** @var ClientRepositoryInterface $repository */
        $repository = self::getContainer()->get(ClientRepositoryInterface::class);
        $repository->save($entity);

        $client = ClientFactory::repository()->find($id)->_real();

        self::assertEquals($entity, $client);
    }
}
