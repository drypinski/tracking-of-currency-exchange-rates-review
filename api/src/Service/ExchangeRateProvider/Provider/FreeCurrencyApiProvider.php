<?php

namespace App\Service\ExchangeRateProvider\Provider;

use Symfony\Contracts\HttpClient\HttpClientInterface;

final class FreeCurrencyApiProvider implements ProviderInterface
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $freecurrencyapiClient)
    {
        $this->client = $freecurrencyapiClient;
    }

    public function getRates(string $baseCurrencyCode, array $quoteCurrenciesCodes): array
    {
        $apiResponse = $this->client->request('GET', '/v1/latest', [
            'query' => [
                'base_currency' => $baseCurrencyCode,
                'currencies' => implode(',', $quoteCurrenciesCodes),
            ],
        ]);

        $response = $apiResponse->toArray();
        $rates = $response['data'] ?? [];

        $result = [];

        foreach ($quoteCurrenciesCodes as $quoteCurrenciesCode) {
            if (isset($rates[$quoteCurrenciesCode])) {
                $result[\sprintf('%s/%s', $baseCurrencyCode, $quoteCurrenciesCode)] = $rates[$quoteCurrenciesCode];
            }
        }

        return $result;
    }
}
