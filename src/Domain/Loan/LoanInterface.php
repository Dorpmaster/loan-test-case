<?php

declare(strict_types=1);

namespace App\Domain\Loan;

use Symfony\Component\Uid\Uuid;

interface LoanInterface
{
    public function getId(): Uuid;
    public function setId(Uuid $id): self;

    public function getProductName(): string;
    public function setProductName(string $name): self;

    public function getTerm(): int;
    public function setTerm(int $term): self;

    public function getInterestRate(): float;
    public function setInterestRate(float $rate): self;
    public function getAmount(): float;
    public function setAmount(float $amount): self;
}
