<?php

declare(strict_types=1);

namespace App\Domain\Loan\Service;

use App\Domain\Client\ClientInterface;
use App\Domain\Loan\LoanInterface;

interface LoanIssuedNotificatorInterface
{
    public function notify(ClientInterface $client, LoanInterface $loan): void;
}
