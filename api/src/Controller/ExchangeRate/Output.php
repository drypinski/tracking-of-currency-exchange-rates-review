<?php

namespace App\Controller\ExchangeRate;

use App\Entity\ExchangeRate\ExchangeRate;
use OpenApi\Attributes as OA;

final readonly class Output
{
    public function __construct(
        #[OA\Property(example: 'USD/EUR')]
        public string $pair,
        #[OA\Property(
            properties: [
                new OA\Property(
                    property: 'Y-m-d H:i',
                    type: 'float',
                    example: 0.9569601041
                ),
            ],
            type: 'object',
        )]
        public array $rates
    ) {}

    /**
     * @param array<ExchangeRate> $rates
     */
    public static function fromExchangeRates(string $pair, array $rates): self
    {
        $items = [];

        foreach ($rates as $rate) {
            $items[$rate->getCreatedAt()->format('Y-m-d H:i')] = $rate->getValue();
        }

        return new self($pair, $items);
    }
}
