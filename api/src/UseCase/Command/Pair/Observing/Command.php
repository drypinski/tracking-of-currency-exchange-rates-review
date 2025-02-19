<?php

namespace App\UseCase\Command\Pair\Observing;

use App\Entity\Currency\Field\Code;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class Command
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Choice(choices: Code::CODES)]
        public string $baseCurrencyCode,
        #[Assert\NotBlank]
        #[Assert\Choice(choices: Code::CODES)]
        #[Assert\NotEqualTo(propertyPath: 'baseCurrencyCode')]
        public string $quoteCurrencyCode,
        public bool $observe
    ) {}
}
