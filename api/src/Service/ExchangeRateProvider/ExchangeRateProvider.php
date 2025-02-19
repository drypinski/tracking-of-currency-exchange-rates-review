<?php

namespace App\Service\ExchangeRateProvider;

use App\Service\ExchangeRateProvider\Provider\ProviderInterface;

final readonly class ExchangeRateProvider implements ExchangeRateProviderInterface
{
    public function __construct(private ProviderInterface $provider) {}

    public function getRates(string $baseCurrencyCode, array $quoteCurrenciesCodes): array
    {
        return $this->provider->getRates($baseCurrencyCode, $quoteCurrenciesCodes);
    }
}
