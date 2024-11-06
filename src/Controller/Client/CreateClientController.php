<?php

declare(strict_types=1);

namespace App\Controller\Client;

use App\Domain\Client\Command\CreateClientFromDtoCommandInterface;
use App\Dto\Client\ClientDto;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[AsController]
final readonly class CreateClientController
{
    public function __construct(
        private CreateClientFromDtoCommandInterface $command,
        private SerializerInterface $serializer,
    ) {
    }

    #[Route(path: '/clients', name: 'create_client', methods: ['POST'])]
    public function __invoke(
        #[MapRequestPayload] ClientDto $payload
    ): Response {
        $client = $this->command->create($payload);

        $response = new Response(
            $this->serializer->serialize($client, 'json'),
            Response::HTTP_CREATED,
        );

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
