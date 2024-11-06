<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Enum\Transport;
use App\Message\LoanIssuedNotification;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class EmailNotificationHandler
{
    public function __invoke(LoanIssuedNotification $message): void
    {
        if (in_array(Transport::EMAIL, $message->getTransports()) === false) {
            return;
        }

        // Here we send the email notification using the service, injected via constructor.
    }
}
