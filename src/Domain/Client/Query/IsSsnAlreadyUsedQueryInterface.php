<?php

declare(strict_types=1);

namespace App\Domain\Client\Query;

interface IsSsnAlreadyUsedQueryInterface
{
    public function query(string $ssn): bool;
}
