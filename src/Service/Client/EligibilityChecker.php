<?php

declare(strict_types=1);

namespace App\Service\Client;

use App\Domain\Client\ClientInterface;
use App\Domain\Client\Query\GetMonthlyIncomeQueryInterface;
use App\Domain\Client\Service\EligibilityCheckerInterface;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class EligibilityChecker implements EligibilityCheckerInterface
{
    public function __construct(
        private ValidatorInterface $validator,
        private GetMonthlyIncomeQueryInterface $getMonthlyIncomeQuery,
    ) {
    }


    public function check(ClientInterface $client): ConstraintViolationListInterface
    {
        $violations = new ConstraintViolationList();

        $income = $this->getMonthlyIncomeQuery->query($client);
        $violations->addAll($this->validator->validate(
            $income,
            [
                new Positive(message: 'Monthly income must be greater than 1000$'),
                new GreaterThanOrEqual(value: 1000, message: 'Monthly income must be greater than 1000$'),
            ],
        ));

        $violations->addAll($this->validator->validate(
            $client->getFico(),
            [
                new Positive(message: 'FICO must be greater than 500'),
                new GreaterThanOrEqual(value: 500, message: 'FICO must be greater than 500'),
            ],
        ));

        $violations->addAll($this->validator->validate(
            $client->getAge(),
            [
                new Range(notInRangeMessage: 'Age must be in 18 - 60 range', min: 18, max: 60),
            ],
        ));

        $state = $client->getAddress()->getState();
        $violations->addAll($this->validator->validate(
            $state,
            [
                new Choice(choices: ['CA', 'NY', 'NV'], message: 'Allowed states: CA, NY, NV'),
            ],
        ));

        if ($state === 'NY') {
            $violations->addAll($this->validator->validate(
                random_int(1, 10),
                [
                    new GreaterThanOrEqual(value: 5, message: 'You are unlucky'),
                ],
            ));
        }

        return $violations;
    }
}
