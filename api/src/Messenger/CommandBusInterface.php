<?php

namespace App\Messenger;

interface CommandBusInterface
{
    public function dispatch(object $message): mixed;
}
