<?php

namespace App\Entity\Currency;

use App\Entity\Currency\Field\Code;
use App\Entity\Currency\Field\CodeType;
use App\Entity\Currency\Field\Id;
use App\Entity\Currency\Field\IdType;
use App\Repository\Doctrine\CurrencyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurrencyRepository::class)]
class Currency
{
    #[ORM\Id]
    #[ORM\Column(type: IdType::NAME, unique: true)]
    private Id $id;

    #[ORM\Column(type: CodeType::NAME, length: 3, unique: true)]
    private Code $code;

    public function __construct(Id $id, Code $code)
    {
        $this->id = $id;
        $this->code = $code;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getCode(): Code
    {
        return $this->code;
    }
}
