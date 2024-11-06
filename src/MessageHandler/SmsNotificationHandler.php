<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Enum\Transport;
use App\Message\LoanIssuedNotification;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class SmsNotificationHandler
{
    public function __invoke(LoanIssuedNotification $message): void
    {
        if (in_array(Transport::SMS, $message->getTransports()) === false) {
            return;
        }

        // Here we send SMS notification using the client of third party API, injected via constructor.
    }
}
