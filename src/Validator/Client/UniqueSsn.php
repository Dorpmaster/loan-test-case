<?php

declare(strict_types=1);

namespace App\Validator\Client;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
final class UniqueSsn extends Constraint
{
    public string $message = 'Client with such SSN already exist.';

    public function __construct(
        mixed $options = null,
        string|null $message = null,
        array|null $groups = null,
        mixed $payload = null
    ) {
        parent::__construct($options, $groups, $payload);

        $this->message = $message ?? $this->message;
    }
}
