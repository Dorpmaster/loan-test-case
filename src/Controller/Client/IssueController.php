<?php

declare(strict_types=1);

namespace App\Controller\Client;

use App\Domain\Client\Exception\ClientDoesNotExistException;
use App\Domain\Client\Query\GetClientByIdQueryInterface;
use App\Domain\Client\Service\EligibilityCheckerInterface;
use App\Domain\Loan\Command\IssueLoanCommandInterface;
use App\Domain\Loan\Exception\DeniedLoanException;
use App\Domain\Loan\Query\GetLoanPropositionQueryInterface;
use App\Domain\Loan\Service\LoanIssuedNotificatorInterface;
use App\Dto\Loan\IssueLoanDto;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Exception\ValidationFailedException;

#[AsController]
final readonly class IssueController
{
    public function __construct(
        private GetClientByIdQueryInterface $getClientByIdQuery,
        private EligibilityCheckerInterface $checker,
        private GetLoanPropositionQueryInterface $getLoanPropositionQuery,
        private IssueLoanCommandInterface $issueLoanCommand,
        private LoanIssuedNotificatorInterface $notificator,
        private SerializerInterface $serializer,
    ) {
    }

    #[Route(path: '/clients/{id}/issue', name: 'issue_loan', methods: ['POST'])]
    public function __invoke(
        Uuid $id,
        #[MapRequestPayload] IssueLoanDto $payload,
    ): Response {
        try {
            $client = $this->getClientByIdQuery->query($id);
        } catch (ClientDoesNotExistException) {
            throw new NotFoundHttpException('Client with such ID does not exist');
        }

        $violations = $this->checker->check($client);

        if (count($violations) > 0) {
            throw HttpException::fromStatusCode(
                Response::HTTP_CONFLICT,
                implode("\n", array_map(
                    static fn ($e) => $e->getMessage(),
                    iterator_to_array($violations)
                )),
                new ValidationFailedException($client, $violations)
            );
        }

        try {
            $proposition = $this->getLoanPropositionQuery->query($client, $payload->propositionId);
            // Bank can update the proposition
            $loan = $this->issueLoanCommand->issue($proposition->getId());
        } catch (DeniedLoanException $exception) {
            // Bank can deny the issue
            throw HttpException::fromStatusCode(
                Response::HTTP_CONFLICT,
                $exception->getMessage(),
                $exception,
            );
        }

        try {
            $this->notificator->notify($client, $loan);
        } finally {
            // We must sed the response regardless the notification result
            $response = new Response(
                $this->serializer->serialize($loan, 'json'),
                Response::HTTP_CREATED,
            );

            $response->headers->set('Content-Type', 'application/json');
        }

        return $response;
    }
}
