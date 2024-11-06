<?php

declare(strict_types=1);

namespace App\Domain\Client;

use App\Domain\Address\AddressInterface;
use Symfony\Component\Uid\Uuid;

interface ClientInterface
{
    public function getId(): Uuid;
    public function setId(Uuid $uuid): self;
    public function getLastName(): string;
    public function setLastName(string $name): self;
    public function getFirstName(): string;
    public function setFirstName(string $name): self;
    public function getAge(): int;
    public function setAge(int $age): self;

    public function getAddress(): AddressInterface;
    public function setAddress(AddressInterface $address): self;

    public function getSsn(): string;
    public function setSsn(string $ssn): self;

    public function getFico(): int;
    public function setFico(int $fico): self;

    public function getEmail(): string;
    public function setEmail(string $email): self;

    public function getPhone(): string;
    public function setPhone(string $phone): self;
}
