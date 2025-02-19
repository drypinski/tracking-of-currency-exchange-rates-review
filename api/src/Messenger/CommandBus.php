<?php

namespace App\Messenger;

use Symfony\Component\Messenger\MessageBusInterface;

final readonly class CommandBus implements CommandBusInterface
{
    public function __construct(private MessageBusInterface $commandBus) {}

    public function dispatch(object $message): mixed
    {
        return $this->commandBus->dispatch($message);
    }
}
