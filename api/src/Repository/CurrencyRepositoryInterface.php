<?php

namespace App\Repository;

use App\Entity\Currency\Currency;
use App\Entity\Currency\Field\Code;
use App\Entity\Currency\Field\Id;
use App\Exception\CurrencyNotFoundException;

interface CurrencyRepositoryInterface
{
    /**
     * @throws CurrencyNotFoundException
     */
    public function get(Id $id): Currency;

    public function findOneByCode(Code $code): ?Currency;

    public function hasByCode(Code $code): bool;

    public function persist(Currency $currency): void;
}
