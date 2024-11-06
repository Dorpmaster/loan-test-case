<?php

declare(strict_types=1);

namespace App\Domain\Client\Query;

use App\Domain\Client\ClientInterface;

interface GetMonthlyIncomeQueryInterface
{
    public function query(ClientInterface $client): int;
}
