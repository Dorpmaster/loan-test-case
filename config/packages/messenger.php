<?php

declare(strict_types=1);

use App\Message\LoanIssuedNotification;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    // We are using synchronous transport in this example.
    // In production environment it should be replaced by asynchronous transport.
    $containerConfigurator->extension('framework', [
        'messenger' => [
            'transports' => [
                'sync' => 'sync://',
            ],
            'routing' => [
                LoanIssuedNotification::class => 'sync',
            ],
        ],
    ]);
};
