<?php

declare(strict_types=1);

namespace App\Tests\Unit\Command\Client;

use App\Command\Client\CreateClientFromDtoCommand;
use App\Domain\Client\ClientInterface;
use App\Domain\Client\Repository\ClientRepositoryInterface;
use App\Dto\Address\AddressDto;
use App\Dto\Client\ClientDto;
use PHPUnit\Framework\TestCase;

final class CreateClientFromDtoCommandTest extends TestCase
{
    public function testCreate(): void
    {
        $addressDto = new AddressDto(
            'Boston',
            'AL',
            12345,
        );
        $clientDto  = new ClientDto(
            'Doe',
            'John',
            18,
            $addressDto,
            '123-12-1234',
            700,
            'email@example.com',
            '+11234567890',
        );

        $repository = self::createMock(ClientRepositoryInterface::class);
        $repository
            ->expects($this->once())
            ->method('save');

        $command = new CreateClientFromDtoCommand($repository);
        $client  = $command->create($clientDto);

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
