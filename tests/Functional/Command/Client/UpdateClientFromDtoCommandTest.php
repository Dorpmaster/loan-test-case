<?php

declare(strict_types=1);

namespace App\Tests\Functional\Command\Client;

use App\Domain\Client\ClientInterface;
use App\Domain\Client\Command\UpdateClientFromDtoCommandInterface;
use App\Dto\Address\AddressDto;
use App\Dto\Client\UpdateClientDto;
use App\Tests\Factory\ClientFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class UpdateClientFromDtoCommandTest extends KernelTestCase
{
    use ResetDatabase;
    use Factories;

    public function testCreate(): void
    {
        $client = ClientFactory::createOne()
          ->_real();

        $addressDto = new AddressDto(
            'Boston',
            'AL',
            12345,
        );
        $clientDto  = new UpdateClientDto(
            'Doe',
            'John',
            18,
            $addressDto,
            '123-12-1234',
            700,
            'email@example.com',
            '+11234567890',
        );

        $command = self::getContainer()->get(UpdateClientFromDtoCommandInterface::class);
        $command->update($clientDto, $client);

        self::assertInstanceOf(ClientInterface::class, $client);
        self::assertSame($clientDto->lastName, $client->getLastName());
        self::assertSame($clientDto->firstName, $client->getFirstName());
        self::assertSame($clientDto->age, $client->getAge());
        self::assertSame($clientDto->address->city, $client->getAddress()->getCity());
        self::assertSame($clientDto->address->state, $client->getAddress()->getState());
        self::assertSame($clientDto->address->zip, $client->getAddress()->getZip());
        self::assertSame($clientDto->ssn, $client->getSsn());
        self::assertSame($clientDto->fico, $client->getFico());
        self::assertSame($clientDto->email, $client->getEmail());
        self::assertSame($clientDto->phone, $client->getPhone());
    }
}
