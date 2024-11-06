<?php

declare(strict_types=1);

use App\Command\Client\CreateClientFromDtoCommand;
use App\Command\Client\UpdateClientFromDtoCommand;
use App\Domain\Client\Command\CreateClientFromDtoCommandInterface;
use App\Domain\Client\Command\UpdateClientFromDtoCommandInterface;
use App\Domain\Client\Query\GetClientByIdQueryInterface;
use App\Domain\Client\Query\IsSsnAlreadyUsedQueryInterface;
use App\Domain\Client\Repository\ClientRepositoryInterface;
use App\Domain\Client\Service\EligibilityCheckerInterface;
use App\Query\Client\GetClientByIdQuery;
use App\Query\Client\IsSsnAlreadyUsedQuery;
use App\Repository\Client\ClientRepository;
use App\Service\Client\EligibilityChecker;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure()
        ->public()

        ->set(ClientRepositoryInterface::class)
            ->class(ClientRepository::class)

        ->set(CreateClientFromDtoCommandInterface::class)
            ->class(CreateClientFromDtoCommand::class)

        ->set(GetClientByIdQueryInterface::class)
            ->class(GetClientByIdQuery::class)

        ->set(IsSsnAlreadyUsedQueryInterface::class)
            ->class(IsSsnAlreadyUsedQuery::class)

        ->set(UpdateClientFromDtoCommandInterface::class)
            ->class(UpdateClientFromDtoCommand::class)

        ->set(EligibilityCheckerInterface::class)
            ->class(EligibilityChecker::class)
    ;
};
