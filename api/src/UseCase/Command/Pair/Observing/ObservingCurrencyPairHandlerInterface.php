<?php

namespace App\UseCase\Command\Pair\Observing;

interface ObservingCurrencyPairHandlerInterface
{
    public function handle(Command $command): void;
}
