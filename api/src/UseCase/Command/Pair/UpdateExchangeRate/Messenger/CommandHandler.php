<?php

namespace App\UseCase\Command\Pair\UpdateExchangeRate\Messenger;

use App\Messenger\CommandHandlerInterface;
use App\UseCase\Command\Pair\UpdateExchangeRate\Command;
use App\UseCase\Command\Pair\UpdateExchangeRate\UpdateExchangeRatesHandlerInterface;

final readonly class CommandHandler implements CommandHandlerInterface
{
    public function __construct(private UpdateExchangeRatesHandlerInterface $handler) {}

    public function __invoke(Command $command): void
    {
        $this->handler->handle($command);
    }
}
