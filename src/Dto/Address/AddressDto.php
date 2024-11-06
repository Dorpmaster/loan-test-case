<?php

declare(strict_types=1);

namespace App\Dto\Address;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class AddressDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 100)]
        public string $city,
        #[Assert\NotBlank]
        #[Assert\Length(min: 2, max: 2)]
        public string $state,
        #[Assert\Positive]
        #[Assert\Range(min: 10_0000, max: 99_999)]
        public int $zip,
    ) {
    }
}
