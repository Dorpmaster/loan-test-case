<?php

declare(strict_types=1);

namespace App\Command\Loan;

use App\Domain\Loan\Command\IssueLoanCommandInterface;
use App\Domain\Loan\LoanInterface;
use App\Entity\Loan;
use Symfony\Component\Uid\Uuid;

final readonly class IssueLoanCommand implements IssueLoanCommandInterface
{
    public function issue(Uuid $propositionId): LoanInterface
    {
        /**
         * Here we send a request to an external banking application to issue a loan according to
         * pre-approved loan terms.
         */

//        $loan = $this->thirdPartyApiClient->makeRequest($propositionId);

        $loan = (new Loan())
            ->setId(Uuid::v7())
            ->setProductName('Loan')
            ->setTerm(60)
            ->setInterestRate(24.5)
            ->setAmount(5000.00);

        return $loan;
    }
}
