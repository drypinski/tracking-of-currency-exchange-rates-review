<?php

namespace App\Service\Validator\Http;

use Symfony\Component\HttpKernel\Exception\HttpException;

interface ValidatorInterface
{
    /**
     * @param null|array<string>|string $groups
     *
     * @throws HttpException
     */
    public function validate(mixed $value, null|array|string $groups = null): void;
}
