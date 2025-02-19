<?php

namespace App\Controller\ExchangeRate;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class Input
{
    public function __construct(
        #[Assert\AtLeastOneOf(constraints: [
            new Assert\DateTime(format: 'Y-m-d H:i', message: '"Y-m-d H:i"'),
            new Assert\DateTime(format: 'Y-m-d H', message: '"Y-m-d H"'),
            new Assert\DateTime(format: 'Y-m-d', message: '"Y-m-d"'),
            new Assert\IsNull(message: ''),
        ], message: 'Use one of valid formats:')]
        public ?string $from = null,
        #[Assert\AtLeastOneOf(constraints: [
            new Assert\DateTime(format: 'Y-m-d H:i', message: '"Y-m-d H:i"'),
            new Assert\DateTime(format: 'Y-m-d H', message: '"Y-m-d H"'),
            new Assert\DateTime(format: 'Y-m-d', message: '"Y-m-d"'),
            new Assert\IsNull(message: ''),
        ], message: 'Use one of valid formats:')]
        public ?string $to = null,
    ) {}
}
