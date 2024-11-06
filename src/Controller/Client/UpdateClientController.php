<?php

declare(strict_types=1);

namespace App\Controller\Client;

use App\Domain\Client\Command\UpdateClientFromDtoCommandInterface;
use App\Domain\Client\Exception\ClientDoesNotExistException;
use App\Domain\Client\Query\GetClientByIdQueryInterface;
use App\Domain\Client\Query\IsSsnAlreadyUsedQueryInterface;
use App\Dto\Client\UpdateClientDto;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;

#[AsController]
final readonly class UpdateClientController
{
    public function __construct(
        private GetClientByIdQueryInterface $getClientByIdQuery,
        private IsSsnAlreadyUsedQueryInterface $isSsnAlreadyUsedQuery,
        private UpdateClientFromDtoCommandInterface $updateClientFromDtoCommand,
        private SerializerInterface $serializer,
    ) {
    }

    #[Route(path: '/clients/{id}', name: 'update_client', methods: ['PATCH'])]
    public function __invoke(
        Uuid $id,
        #[MapRequestPayload] UpdateClientDto $payload
    ): Response {
        try {
            $client = $this->getClientByIdQuery->query($id);
        } catch (ClientDoesNotExistException) {
            throw new NotFoundHttpException('Client with such ID does not exist');
        }

        if ($payload->ssn !== null) {
            if ($payload->ssn !== $client->getSsn()) {
                if ($this->isSsnAlreadyUsedQuery->query($payload->ssn) === true) {
                    throw new ConflictHttpException('Client with such SSN already exist');
                }
            }
        }

        $this->updateClientFromDtoCommand->update($payload, $client);

        $response = new Response(
            $this->serializer->serialize($client, 'json'),
            Response::HTTP_OK,
        );

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
