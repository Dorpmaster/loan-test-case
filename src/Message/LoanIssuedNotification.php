<?php

declare(strict_types=1);

namespace App\Message;

use App\Domain\Client\ClientInterface;
use App\Domain\Loan\LoanInterface;
use App\Enum\Transport;
use RuntimeException;

final readonly class LoanIssuedNotification
{
    public function __construct(
        private ClientInterface $client,
        private LoanInterface $loan,
        private array $transports,
    ) {
        $correctTransports = array_filter(
            $this->transports,
            static fn(mixed $item): bool => $item instanceof Transport,
        );

        if (count($correctTransports) === 0) {
            throw new RuntimeException('Incorrect transport');
        }
    }

    /**
     * @return array<int, Transport>
     */
    public function getTransports(): array
    {
        return $this->transports;
    }

    public function getClient(): ClientInterface
    {
        return $this->client;
    }

    public function getLoan(): LoanInterface
    {
        return $this->loan;
    }
}
