<?php

namespace App\Repository\Doctrine;

use App\Entity\ExchangeRate\ExchangeRate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ExchangeRate>
 */
class ExchangeRateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExchangeRate::class);
    }

    public function persist(ExchangeRate $exchangeRate): void
    {
        $this->getEntityManager()->persist($exchangeRate);
    }
}
