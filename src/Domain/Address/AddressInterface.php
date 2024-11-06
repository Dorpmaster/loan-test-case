<?php

declare(strict_types=1);

namespace App\Domain\Address;

interface AddressInterface
{
    public function getCity(): string;
    public function setCity(string $city): self;

    public function getState(): string;
    public function setState(string $state): self;

    public function getZip(): int;
    public function setZip(int $zip): self;
}
