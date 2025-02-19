<?php

namespace App\Repository\Doctrine;

use App\Entity\Pair\Pair;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pair>
 */
class PairRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pair::class);
    }

    public function persist(Pair $pair): void
    {
        $this->getEntityManager()->persist($pair);
    }
}
