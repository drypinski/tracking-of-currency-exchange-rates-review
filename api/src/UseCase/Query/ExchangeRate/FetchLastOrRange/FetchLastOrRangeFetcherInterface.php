<?php

namespace App\UseCase\Query\ExchangeRate\FetchLastOrRange;

use App\Entity\ExchangeRate\ExchangeRate;

interface FetchLastOrRangeFetcherInterface
{
    /**
     * @return array<ExchangeRate>
     */
    public function fetch(Query $query): array;
}
