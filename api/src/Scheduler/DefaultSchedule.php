<?php

namespace App\Scheduler;

use App\UseCase\Command\Pair\UpdateObservedRates\Command;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;

#[AsSchedule]
final readonly class DefaultSchedule implements ScheduleProviderInterface
{
    public function __construct(
        #[Autowire(param: 'exchange_rate.lifetime')]
        private int $exchangeRateLifetime
    ) {}

    public function getSchedule(): Schedule
    {
        return (new Schedule())->add(
            RecurringMessage::every(\sprintf('%d seconds', $this->exchangeRateLifetime), new Command()),
        );
    }
}
