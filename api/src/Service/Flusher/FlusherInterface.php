<?php

namespace App\Service\Flusher;

interface FlusherInterface
{
    public function flush(object $entity): void;

    public function flushMany(object ...$entities): void;
}
