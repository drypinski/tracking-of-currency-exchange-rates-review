<?php

namespace App\Service\ExchangeRateProvider;

interface ExchangeRateProviderInterface
{
    /**
     * Response example:
     *
     *  [
     *      'USD/EUR' => 0.9534201681,
     *      'USD/RUB' => 91.4928443457,
     *  ]
     *
     * @return array<string, float>
     */
    public function getRates(string $baseCurrencyCode, array $quoteCurrenciesCodes): array;
}
