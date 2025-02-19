<?php

namespace App\UseCase\Command\Pair\UpdateExchangeRate;

interface UpdateExchangeRatesHandlerInterface
{
    public function handle(Command $command): void;
}
