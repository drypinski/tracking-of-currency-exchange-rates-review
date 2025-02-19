<?php

namespace App\UseCase\Command\Pair\UpdateExchangeRate;

use App\Entity\Currency\Field\Code;
use App\Entity\ExchangeRate\ExchangeRate;
use App\Entity\ExchangeRate\Field\Id as ExchangeRateId;
use App\Entity\Pair\Pair;
use App\Repository\ExchangeRateRepositoryInterface;
use App\Repository\PairRepositoryInterface;
use App\Service\ExchangeRateProvider\ExchangeRateProviderInterface;
use App\Service\Flusher\FlusherInterface;
use App\Service\Validator\ValidatorInterface;
use DateTimeImmutable;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final readonly class Handler implements UpdateExchangeRatesHandlerInterface
{
    public function __construct(
        private ValidatorInterface $validator,
        private PairRepositoryInterface $pairRepository,
        private ExchangeRateRepositoryInterface $exchangeRateRepository,
        private ExchangeRateProviderInterface $exchangeRateProvider,
        private FlusherInterface $flusher,
        #[Autowire(param: 'exchange_rate.lifetime')]
        private int $rateLifetimeInSeconds
    ) {}

    public function handle(Command $command): void
    {
        $this->validator->validate($command);

        $base = new Code($command->baseCurrencyCode);
        $quotes = array_map(static fn (string $code) => new Code($code), $command->quoteCurrencyCodes);

        $pairs = $this->findExpiredPairs($base, $quotes);

        $quoteCurrenciesCodes = [];

        foreach ($pairs as $pair) {
            $quoteCurrenciesCodes[] = $pair->getQuote()->getCode()->getValue();
        }

        if (empty($quoteCurrenciesCodes)) {
            return;
        }

        $rates = $this->getNewRates($base->getValue(), $quoteCurrenciesCodes);
        $exchangeRates = [];

        foreach ($pairs as $pair) {
            $key = \sprintf('%s/%s', $base->getValue(), $pair->getQuote()->getCode()->getValue());

            if (isset($rates[$key])) {
                $pair->setUpdatedAt($updatedAt = new DateTimeImmutable());
                $exchangeRate = new ExchangeRate(ExchangeRateId::create(), $pair, $updatedAt, $rates[$key]);
                $this->exchangeRateRepository->persist($exchangeRate);
                $exchangeRates[] = $exchangeRate;
            }
        }

        $this->flusher->flushMany(...$exchangeRates);
    }

    /**
     * @return array<Pair>
     */
    private function findExpiredPairs(Code $base, array $quotes): array
    {
        $validDate = new DateTimeImmutable(\sprintf('-%d seconds', $this->rateLifetimeInSeconds - 5));

        $pairs = $this->pairRepository->findObservablesByBaseAndQuotes($base, $quotes);

        $expiredRates = static fn (Pair $pair) => null === $pair->getUpdatedAt() || $pair->getUpdatedAt() <= $validDate;

        return array_filter($pairs, $expiredRates);
    }

    /**
     * @return array<string, float>
     */
    private function getNewRates(string $baseCurrencyCode, array $quoteCurrenciesCodes): array
    {
        return $this->exchangeRateProvider->getRates($baseCurrencyCode, $quoteCurrenciesCodes);
    }
}
