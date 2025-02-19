<?php

namespace App\UseCase\Command\Pair\UpdateObservedRates\Messenger;

use App\Messenger\CommandHandlerInterface;
use App\UseCase\Command\Pair\UpdateObservedRates\Command;
use App\UseCase\Command\Pair\UpdateObservedRates\UpdateObservedRatesHandlerInterface;

final readonly class CommandHandler implements CommandHandlerInterface
{
    public function __construct(private UpdateObservedRatesHandlerInterface $handler) {}

    public function __invoke(Command $command): void
    {
        $this->handler->handle($command);
    }
}
