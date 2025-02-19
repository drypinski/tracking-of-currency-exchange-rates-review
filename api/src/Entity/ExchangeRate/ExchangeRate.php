<?php

namespace App\Entity\ExchangeRate;

use App\Entity\ExchangeRate\Field\Id;
use App\Entity\ExchangeRate\Field\IdType;
use App\Entity\Pair\Pair;
use App\Repository\Doctrine\ExchangeRateRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExchangeRateRepository::class)]
class ExchangeRate
{
    #[ORM\Id]
    #[ORM\Column(type: IdType::NAME, unique: true)]
    private Id $id;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Pair $pair;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    #[ORM\Column]
    private float $value;

    public function __construct(Id $id, Pair $pair, DateTimeImmutable $createdAt, float $value)
    {
        $this->id = $id;
        $this->pair = $pair;
        $this->createdAt = $createdAt;
        $this->value = $value;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getPair(): Pair
    {
        return $this->pair;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getValue(): float
    {
        return $this->value;
    }
}
