<?php

namespace App\UseCase\Query\ExchangeRate\FetchLastOrRange;

use App\Entity\Currency\Field\Code;
use DateTimeInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class Query
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Choice(choices: Code::CODES)]
        public string $baseCurrencyCode,
        #[Assert\NotBlank]
        #[Assert\Choice(choices: Code::CODES)]
        public string $quoteCurrencyCode,
        public ?DateTimeInterface $from,
        public ?DateTimeInterface $to,
    ) {}
}
