<?php

namespace App\UseCase\Command\Pair\UpdateExchangeRate;

use App\Entity\Currency\Field\Code;
use Symfony\Component\Messenger\Attribute\AsMessage;
use Symfony\Component\Validator\Constraints as Assert;

#[AsMessage('async')]
final readonly class Command
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Choice(choices: Code::CODES)]
        public string $baseCurrencyCode,
        #[Assert\NotBlank]
        #[Assert\All([
            new Assert\NotBlank(),
            new Assert\Choice(choices: Code::CODES),
        ])]
        public array $quoteCurrencyCodes,
    ) {}
}
