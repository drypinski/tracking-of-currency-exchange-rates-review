<?php

namespace App\Repository;

use App\Entity\Currency\Field\Code;
use App\Entity\Pair\Field\Id;
use App\Entity\Pair\Pair;
use App\Exception\PairNotFoundException;
use DateTimeInterface;

interface PairRepositoryInterface
{
    /**
     * @throws PairNotFoundException
     */
    public function get(Id $id): Pair;

    public function findOneByCodes(Code $base, Code $quote): ?Pair;

    /**
     * @param array<Code> $quotes
     *
     * @return array<Pair>
     */
    public function findObservablesByBaseAndQuotes(Code $base, array $quotes): array;

    /**
     * @return array<Pair>
     */
    public function findObservablesBeforeDate(DateTimeInterface $date): array;

    public function persist(Pair $pair): void;
}
