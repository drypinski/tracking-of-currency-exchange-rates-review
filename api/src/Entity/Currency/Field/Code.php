<?php

namespace App\Entity\Currency\Field;

use Webmozart\Assert\Assert;

final class Code
{
    public const array CODES = [
        'USD', 'EUR', 'RUB',
    ];

    private string $value;

    public function __construct(string $value)
    {
        $this->value = strtoupper($value);

        Assert::oneOf($this->value, self::CODES);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
