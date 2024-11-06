<?php

declare(strict_types=1);

namespace App\Entity;

use App\Domain\Address\AddressInterface;
use App\Domain\Client\ClientInterface;
use Doctrine\ORM\Mapping as ORM;
use RuntimeException;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'clients')]
#[ORM\UniqueConstraint(name: 'idx_client_ssn', columns: ['ssn'])]
class Client implements ClientInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private Uuid $id;

    #[ORM\Column(type: 'string', length: 100)]
    private string $lastName;

    #[ORM\Column(type: 'string', length: 100)]
    private string $firstName;

    #[ORM\Column(type: 'integer')]
    private int $age;

    #[ORM\Column(type: 'string', length: 255)]
    private string $address;

    #[ORM\Column(type: 'string', length: 11, unique: true)]
    private string $ssn;

    #[ORM\Column(type: 'integer')]
    private int $fico;

    #[ORM\Column(type: 'string', length: 255)]
    private string $email;

    #[ORM\Column(type: 'string', length: 15)]
    private string $phone;

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $uuid): self
    {
        $this->id = $uuid;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $name): self
    {
        $this->lastName = $name;

        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $name): self
    {
        $this->firstName = $name;

        return $this;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getAddress(): AddressInterface
    {
        $values = array_filter(array_map('trim', explode(',', $this->address, 3)));
        if (count($values) !== 3) {
            throw new RuntimeException('Wrong address value');
        }

        return (new Address())
            ->setCity($values[0])
            ->setState($values[1])
            ->setZip((int)$values[2]);
    }

    public function setAddress(AddressInterface $address): self
    {
        $this->address = implode(',', [$address->getCity(), $address->getState(), (string) $address->getZip()]);

        return $this;
    }

    public function getSsn(): string
    {
        return $this->ssn;
    }

    public function setSsn(string $ssn): self
    {
        $this->ssn = $ssn;

        return $this;
    }

    public function getFico(): int
    {
        return $this->fico;
    }

    public function setFico(int $fico): self
    {
        $this->fico = $fico;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }
}
