<?php

namespace App\Tests\Factory;

use App\Entity\Address;
use App\Entity\Client;
use Symfony\Component\Uid\Uuid;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Client>
 */
final class ClientFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Client::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'id' => Uuid::v7(),
            'address' => (new Address())
                ->setCity(self::faker()->city())
                ->setState(self::faker()->stateAbbr())
                ->setZip((int) self::faker()->postcode())
            ,
            'age' => self::faker()->numberBetween(18, 60),
            'email' => self::faker()->email(),
            'fico' => self::faker()->numberBetween(300, 850),
            'firstName' => self::faker()->firstName(),
            'lastName' => self::faker()->lastName(),
            'phone' => self::faker()->e164PhoneNumber(),
            'ssn' => implode('-', [
                self::faker()->randomNumber(3, true),
                self::faker()->randomNumber(2, true),
                self::faker()->randomNumber(4, true),
            ])
        ];
    }
}
