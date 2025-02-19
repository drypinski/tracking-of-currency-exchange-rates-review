<?php

namespace App\UseCase\Command\Pair\UpdateObservedRates;

interface UpdateObservedRatesHandlerInterface
{
    public function handle(Command $command): void;
}
