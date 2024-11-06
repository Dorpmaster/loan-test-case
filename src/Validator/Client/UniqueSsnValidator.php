<?php

declare(strict_types=1);

namespace App\Validator\Client;

use App\Domain\Client\Repository\ClientRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class UniqueSsnValidator extends ConstraintValidator
{
    public function __construct(
        private readonly ClientRepositoryInterface $repository,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueSsn) {
            throw new UnexpectedTypeException($constraint, UniqueSsn::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if ($this->repository->isSsnExist($value) === false) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->addViolation();
    }
}
