<?php

declare(strict_types=1);

namespace App\Query\Loan;

use App\Domain\Client\ClientInterface;
use App\Domain\Loan\LoanInterface;
use App\Domain\Loan\Query\GetLoanPropositionQueryInterface;
use App\Entity\Loan;
use Symfony\Component\Uid\Uuid;

final readonly class GetLoanPropositionQuery implements GetLoanPropositionQueryInterface
{
    public function query(ClientInterface $client, ?Uuid $propositionId = null): LoanInterface
    {
        /**
         * Here we send a request to an external banking application to obtain pre-approved loan terms.
         * The loan identifier allows us to subsequently request loan issue or check the eligibility of applying
         * these terms for the client.
         */

        $additionalTerms = [];

        if ($client->getAddress()->getState() === 'CA') {
            // For clients from California, we are increasing the interest rate by 11.49%.
            $additionalTerms['increaseRate'] = 11.49;
        }

//        $options = [
//            'propositionId' => $propositionId,
//            'client' => $client,
//            'additionalTerms' => $additionalTerms,
//        ];
//
//        $loan = $this->thirdPartyApiClient->makeRequest($options);

        $loan = (new Loan())
            ->setId(Uuid::v7())
            ->setProductName('Loan')
            ->setTerm(60)
            ->setInterestRate(24.5)
            ->setAmount(5000.00);

        return $loan;
    }
}
