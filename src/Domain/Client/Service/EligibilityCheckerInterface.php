<?php

declare(strict_types=1);

namespace App\Domain\Client\Service;

use App\Domain\Client\ClientInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

interface EligibilityCheckerInterface
{
    public function check(ClientInterface $client): ConstraintViolationListInterface;
}
