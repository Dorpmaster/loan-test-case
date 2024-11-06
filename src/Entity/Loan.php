<?php

declare(strict_types=1);

namespace App\Entity;

use App\Domain\Loan\LoanInterface;
use Symfony\Component\Uid\Uuid;

class Loan implements LoanInterface
{
    private Uuid $id;
    private string $productName;
    private int $term;
    private float $interestRate;
    private float $amount;

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function setProductName(string $name): self
    {
        $this->productName = $name;

        return $this;
    }

    public function getTerm(): int
    {
        return $this->term;
    }

    public function setTerm(int $term): self
    {
        $this->term = $term;

        return $this;
    }

    public function getInterestRate(): float
    {
        return $this->interestRate;
    }

    public function setInterestRate(float $rate): self
    {
        $this->interestRate = $rate;

        return $this;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): LoanInterface
    {
        $this->amount = $amount;

        return $this;
    }
}
