<?php

namespace App\Exception;

use DomainException;
use Throwable;

abstract class EntityNotFoundException extends DomainException
{
    private int $id;

    public function __construct(string $id, string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->id = $id;
    }

    final public function getId(): string
    {
        return $this->id;
    }
}
