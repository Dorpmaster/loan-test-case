<?php

declare(strict_types=1);

namespace App\Domain\Loan\Command;

use App\Domain\Loan\Exception\DeniedLoanException;
use App\Domain\Loan\LoanInterface;
use Symfony\Component\Uid\Uuid;

interface IssueLoanCommandInterface
{
    /**
     * @throws DeniedLoanException
     */
    public function issue(Uuid $propositionId): LoanInterface;
}
