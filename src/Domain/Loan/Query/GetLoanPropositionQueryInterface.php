<?php

declare(strict_types=1);

namespace App\Domain\Loan\Query;

use App\Domain\Client\ClientInterface;
use App\Domain\Loan\Exception\DeniedLoanException;
use App\Domain\Loan\LoanInterface;
use Symfony\Component\Uid\Uuid;

interface GetLoanPropositionQueryInterface
{
    /**
     * @throws DeniedLoanException
     */
    public function query(ClientInterface $client, Uuid|null $propositionId = null): LoanInterface;
}
