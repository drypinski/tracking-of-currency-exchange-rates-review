<?php

namespace App\Service\Flusher;

use Doctrine\ORM\EntityManagerInterface;

final readonly class Flusher implements FlusherInterface
{
    public function __construct(private EntityManagerInterface $em) {}

    public function flush(object $entity): void
    {
        $this->em->flush();
    }

    public function flushMany(object ...$entities): void
    {
        $this->em->flush();
    }
}
