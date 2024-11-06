<?php

declare(strict_types=1);

namespace App\Dto\Loan;

use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class IssueLoanDto
{
    public function __construct(
        #[Assert\Uuid]
        public Uuid|null $propositionId = null
    ) {
    }
}
