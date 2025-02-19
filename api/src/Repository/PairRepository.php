<?php

namespace App\Repository;

use App\Entity\Currency\Field\Code;
use App\Entity\Pair\Field\Id;
use App\Entity\Pair\Pair;
use App\Exception\PairNotFoundException;
use App\Repository\Doctrine\PairRepository as DoctrinePairRepository;
use DateTimeInterface;
use Doctrine\DBAL\ArrayParameterType;

final readonly class PairRepository implements PairRepositoryInterface
{
    public function __construct(private DoctrinePairRepository $doctrineRepository) {}

    public function get(Id $id): Pair
    {
        if (null === $pair = $this->doctrineRepository->find($id)) {
            throw new PairNotFoundException($id->getValue());
        }

        return $pair;
    }

    public function findOneByCodes(Code $base, Code $quote): ?Pair
    {
        $qb = $this->doctrineRepository->createQueryBuilder('root');
        $qb
            ->innerJoin('root.base', 'base')
            ->innerJoin('root.quote', 'quote')
            ->where($qb->expr()->andX(
                $qb->expr()->eq('base.code', ':base'),
                $qb->expr()->eq('quote.code', ':quote'),
            ))
            ->setParameter('base', $base)
            ->setParameter('quote', $quote);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function findObservablesByBaseAndQuotes(Code $base, array $quotes): array
    {
        $qb = $this->doctrineRepository->createQueryBuilder('root');
        $qb
            ->select(['root', 'base', 'quote'])
            ->innerJoin('root.base', 'base')
            ->innerJoin('root.quote', 'quote')
            ->where($qb->expr()->andX(
                $qb->expr()->eq('root.watch', ':watch'),
                $qb->expr()->eq('base.code', ':base'),
                $qb->expr()->in('quote.code', ':quote')
            ))
            ->setParameter('watch', true)
            ->setParameter('base', $base)
            ->setParameter('quote', $quotes, ArrayParameterType::STRING);

        return $qb->getQuery()->getResult();
    }

    public function findObservablesBeforeDate(DateTimeInterface $date): array
    {
        $qb = $this->doctrineRepository->createQueryBuilder('root');
        $qb
            ->where($qb->expr()->andX(
                $qb->expr()->eq('root.watch', ':watch'),
                $qb->expr()->orX(
                    $qb->expr()->isNull('root.updatedAt'),
                    $qb->expr()->lte('root.updatedAt', ':date')
                )
            ))
            ->setParameter('watch', true)
            ->setParameter('date', $date);

        return $qb->getQuery()->getResult();
    }

    public function persist(Pair $pair): void
    {
        $this->doctrineRepository->persist($pair);
    }
}
