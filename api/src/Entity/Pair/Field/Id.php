<?php

namespace App\Entity\Pair\Field;

use Symfony\Component\Uid\Uuid;
use Webmozart\Assert\Assert;

final class Id
{
    private string $value;

    public function __construct(string $value)
    {
        Assert::uuid($value);

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public static function create(): self
    {
        return new self((string) Uuid::v7());
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function isEqual(self $candidate): bool
    {
        return $this->value === $candidate->value;
    }
}
