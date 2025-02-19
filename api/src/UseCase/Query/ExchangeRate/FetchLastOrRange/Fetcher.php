<?php

namespace App\UseCase\Query\ExchangeRate\FetchLastOrRange;

use App\Entity\Currency\Field\Code;
use App\Repository\ExchangeRateRepositoryInterface;
use App\Service\Validator\ValidatorInterface;
use DateTimeImmutable;

final readonly class Fetcher implements FetchLastOrRangeFetcherInterface
{
    public function __construct(
        private ValidatorInterface $validator,
        private ExchangeRateRepositoryInterface $exchangeRateRepository
    ) {}

    public function fetch(Query $query): array
    {
        $this->validator->validate($query);

        $base = new Code($query->baseCurrencyCode);
        $quote = new Code($query->quoteCurrencyCode);

        if (null === $query->from) {
            return $this->exchangeRateRepository->findLast($base, $quote);
        }

        return $this->exchangeRateRepository->findByCreatedAtRange(
            $base,
            $quote,
            $query->from,
            $query->to ?? new DateTimeImmutable()
        );
    }
}
