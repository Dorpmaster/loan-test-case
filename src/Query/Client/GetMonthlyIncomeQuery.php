<?php

declare(strict_types=1);

namespace App\Query\Client;

use App\Domain\Client\ClientInterface;
use App\Domain\Client\Query\GetMonthlyIncomeQueryInterface;

final class GetMonthlyIncomeQuery implements GetMonthlyIncomeQueryInterface
{
    public function query(ClientInterface $client): int
    {
        // John is always broke
        if ('John' === $client->getFirstName()) {
            return 50;
        }

        return 1500;
    }
}
