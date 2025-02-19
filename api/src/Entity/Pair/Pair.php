<?php

namespace App\Entity\Pair;

use App\Entity\Currency\Currency;
use App\Entity\Pair\Field\Id;
use App\Entity\Pair\Field\IdType;
use App\Repository\Doctrine\PairRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: PairRepository::class)]
#[ORM\UniqueConstraint(fields: ['base', 'quote'])]
#[UniqueEntity(fields: ['base', 'quote'])]
class Pair
{
    #[ORM\Id]
    #[ORM\Column(type: IdType::NAME, unique: true)]
    private Id $id;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Currency $base;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Currency $quote;

    #[ORM\Column]
    private bool $watch = false;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $updatedAt = null;

    public function __construct(Id $id, Currency $base, Currency $quote)
    {
        $this->id = $id;
        $this->base = $base;
        $this->quote = $quote;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getBase(): Currency
    {
        return $this->base;
    }

    public function getQuote(): Currency
    {
        return $this->quote;
    }

    public function isWatch(): bool
    {
        return $this->watch;
    }

    public function setWatch(bool $watch): static
    {
        $this->watch = $watch;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
