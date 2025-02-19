<?php

namespace App\Repository;

use App\Entity\Currency\Currency;
use App\Entity\Currency\Field\Code;
use App\Entity\Currency\Field\Id;
use App\Exception\CurrencyNotFoundException;
use App\Repository\Doctrine\CurrencyRepository as DoctrineCurrencyRepository;

final readonly class CurrencyRepository implements CurrencyRepositoryInterface
{
    public function __construct(private DoctrineCurrencyRepository $doctrineRepository) {}

    public function get(Id $id): Currency
    {
        if (null === $currency = $this->doctrineRepository->find($id)) {
            throw new CurrencyNotFoundException($id->getValue());
        }

        return $currency;
    }

    public function findOneByCode(Code $code): ?Currency
    {
        return $this->doctrineRepository->findOneBy(['code' => $code]);
    }

    public function hasByCode(Code $code): bool
    {
        return null !== $this->doctrineRepository->findOneBy(['code' => $code]);
    }

    public function persist(Currency $currency): void
    {
        $this->doctrineRepository->persist($currency);
    }
}
