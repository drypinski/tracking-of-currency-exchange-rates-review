<?php

namespace App\UseCase\Command\Pair\UpdateObservedRates;

use App\Messenger\CommandBusInterface;
use App\Repository\PairRepositoryInterface;
use App\UseCase\Command\Pair\UpdateExchangeRate\Command as UpdateExchangeRateCommand;
use DateTimeImmutable;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final readonly class Handler implements UpdateObservedRatesHandlerInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private PairRepositoryInterface $pairRepository,
        #[Autowire(param: 'exchange_rate.lifetime')]
        private int $exchangeRateLifetime
    ) {}

    public function handle(Command $command): void
    {
        $pairs = $this->getPairs();

        foreach ($pairs as $baseCode => $quoteCodes) {
            $this->commandBus->dispatch(new UpdateExchangeRateCommand($baseCode, $quoteCodes));
        }
    }

    /**
     * @return array<string, array<string>>
     */
    private function getPairs(): array
    {
        $validDate = new DateTimeImmutable(\sprintf('-%d seconds', $this->exchangeRateLifetime - 5));
        $pairs = $this->pairRepository->findObservablesBeforeDate($validDate);

        $result = [];

        foreach ($pairs as $pair) {
            $result[$pair->getBase()->getCode()->getValue()][] = $pair->getQuote()->getCode()->getValue();
        }

        return $result;
    }
}
