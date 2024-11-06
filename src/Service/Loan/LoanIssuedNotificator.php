<?php

declare(strict_types=1);

namespace App\Service\Loan;

use App\Domain\Client\ClientInterface;
use App\Domain\Loan\LoanInterface;
use App\Domain\Loan\Service\LoanIssuedNotificatorInterface;
use App\Enum\Transport;
use App\Message\LoanIssuedNotification;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class LoanIssuedNotificator implements LoanIssuedNotificatorInterface
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    public function notify(ClientInterface $client, LoanInterface $loan): void
    {
        $event = new LoanIssuedNotification($client, $loan, [Transport::SMS, Transport::EMAIL]);

        $this->messageBus->dispatch($event);
    }
}
