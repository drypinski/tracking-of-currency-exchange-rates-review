<?php

namespace App\UseCase\Command\Pair\Observing;

use App\Entity\Currency\Currency;
use App\Entity\Currency\Field\Code;
use App\Entity\Currency\Field\Id as CurrencyId;
use App\Entity\Pair\Field\Id as PairId;
use App\Entity\Pair\Pair;
use App\Repository\CurrencyRepositoryInterface;
use App\Repository\PairRepositoryInterface;
use App\Service\Flusher\FlusherInterface;

final readonly class Handler implements ObservingCurrencyPairHandlerInterface
{
    public function __construct(
        private CurrencyRepositoryInterface $currencyRepository,
        private PairRepositoryInterface $pairRepository,
        private FlusherInterface $flusher
    ) {}

    public function handle(Command $command): void
    {
        if (null !== $pair = $this->findPair($command->baseCurrencyCode, $command->quoteCurrencyCode)) {
            $pair->setWatch($command->observe);
            $this->flusher->flush($pair);

            return;
        }

        $base = $this->findCurrencyOrCreate($command->baseCurrencyCode);
        $quote = $this->findCurrencyOrCreate($command->quoteCurrencyCode);

        $pair = new Pair(PairId::create(), $base, $quote);
        $pair->setWatch($command->observe);

        $this->pairRepository->persist($pair);
        $this->flusher->flush($pair);
    }

    private function findPair(string $baseCode, string $quoteCode): ?Pair
    {
        return $this->pairRepository->findOneByCodes(new Code($baseCode), new Code($quoteCode));
    }

    private function findCurrencyOrCreate(string $code): Currency
    {
        if (null === $currency = $this->currencyRepository->findOneByCode($currencyCode = new Code($code))) {
            $currency = new Currency(CurrencyId::create(), $currencyCode);
            $this->currencyRepository->persist($currency);
        }

        return $currency;
    }
}
