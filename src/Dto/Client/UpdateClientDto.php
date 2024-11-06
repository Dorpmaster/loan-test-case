<?php

declare(strict_types=1);

namespace App\Dto\Client;

use App\Dto\Address\AddressDto;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateClientDto
{
    public function __construct(
        #[Assert\Length(min: 1, max: 100)]
        public string|null $lastName = null,
        #[Assert\Length(min: 1, max: 100)]
        public string|null $firstName = null,
        #[Assert\Positive]
        #[Assert\Range(min: 18, max: 60)]
        public int|null $age = null,
        public AddressDto|null $address = null,
        #[Assert\Length(min: 9, max: 11)]
        #[Assert\Regex(pattern: '/^\d{3}-\d{2}-\d{4}$/')]
        public string|null $ssn = null,
        #[Assert\Positive]
        #[Assert\Range(min: 300, max: 850)]
        public int|null $fico = null,
        #[Assert\Email]
        public string|null $email = null,
        #[Assert\Length(min: 10, max: 15)]
        public string|null $phone = null,
    ) {
    }
}
