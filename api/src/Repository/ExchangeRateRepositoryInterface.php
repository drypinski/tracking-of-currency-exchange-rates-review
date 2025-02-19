<?php

namespace App\Repository;

use App\Entity\Currency\Field\Code;
use App\Entity\ExchangeRate\ExchangeRate;
use App\Entity\ExchangeRate\Field\Id;
use App\Exception\ExchangeRateNotFoundException;
use DateTimeInterface;

interface ExchangeRateRepositoryInterface
{
    /**
     * @throws ExchangeRateNotFoundException
     */
    public function get(Id $id): ExchangeRate;

    /**
     * @return array<ExchangeRate>
     */
    public function findByCreatedAtRange(Code $base, Code $quote, DateTimeInterface $start, DateTimeInterface $end): array;

    public function findLast(Code $base, Code $quote): array;

    public function persist(ExchangeRate $exchangeRate): void;
}
