<?php

namespace App\Repository;

use App\Entity\Currency\Field\Code;
use App\Entity\ExchangeRate\ExchangeRate;
use App\Entity\ExchangeRate\Field\Id;
use App\Exception\ExchangeRateNotFoundException;
use App\Repository\Doctrine\ExchangeRateRepository as DoctrineExchangeRateRepository;
use DateTimeInterface;

final readonly class ExchangeRateRepository implements ExchangeRateRepositoryInterface
{
    public function __construct(private DoctrineExchangeRateRepository $doctrineRepository) {}

    public function get(Id $id): ExchangeRate
    {
        if (null === $exchangeRate = $this->doctrineRepository->find($id)) {
            throw new ExchangeRateNotFoundException($id->getValue());
        }

        return $exchangeRate;
    }

    public function findByCreatedAtRange(Code $base, Code $quote, DateTimeInterface $start, DateTimeInterface $end): array
    {
        $qb = $this->doctrineRepository->createQueryBuilder('root');
        $qb
            ->innerJoin('root.pair', 'pair')
            ->innerJoin('pair.base', 'base')
            ->innerJoin('pair.quote', 'quote')
            ->where($qb->expr()->andX(
                $qb->expr()->eq('base.code', ':base'),
                $qb->expr()->eq('quote.code', ':quote'),
                $qb->expr()->between('root.createdAt', ':start', ':end'),
            ))
            ->setParameter('base', $base)
            ->setParameter('quote', $quote)
            ->setParameter('start', $start)
            ->setParameter('end', $end);

        return $qb->getQuery()->getResult();
    }

    public function findLast(Code $base, Code $quote): array
    {
        $qb = $this->doctrineRepository->createQueryBuilder('root');
        $qb
            ->innerJoin('root.pair', 'pair')
            ->innerJoin('pair.base', 'base')
            ->innerJoin('pair.quote', 'quote')
            ->where($qb->expr()->andX(
                $qb->expr()->eq('base.code', ':base'),
                $qb->expr()->eq('quote.code', ':quote'),
            ))
            ->orderBy('root.createdAt', 'DESC')
            ->setMaxResults(1)
            ->setParameter('base', $base)
            ->setParameter('quote', $quote);

        return $qb->getQuery()->getResult();
    }

    public function persist(ExchangeRate $exchangeRate): void
    {
        $this->doctrineRepository->persist($exchangeRate);
    }
}
