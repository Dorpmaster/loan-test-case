<?php

declare(strict_types=1);

namespace App\Dto\Client;

use App\Dto\Address\AddressDto;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Client\UniqueSsn;

final readonly class ClientDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 100)]
        public string $lastName,
        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 100)]
        public string $firstName,
        #[Assert\Positive]
        #[Assert\Range(min: 18, max: 60)]
        public int $age,
        public AddressDto $address,
        #[Assert\NotBlank]
        #[Assert\Length(min: 9, max: 11)]
        #[Assert\Regex(pattern: '/^\d{3}-\d{2}-\d{4}$/')]
        #[UniqueSsn]
        public string $ssn,
        #[Assert\Positive]
        #[Assert\Range(min: 300, max: 850)]
        public int $fico,
        #[Assert\NotBlank]
        #[Assert\Email]
        public string $email,
        #[Assert\NotBlank]
        #[Assert\Length(min: 10, max: 15)]
        public string $phone,
    ) {
    }
}
