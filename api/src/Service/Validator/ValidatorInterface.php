<?php

namespace App\Service\Validator;

use App\Exception\ValidationFailedException;

interface ValidatorInterface
{
    /**
     * @param null|array<string>|string $groups
     *
     * @throws ValidationFailedException
     */
    public function validate(mixed $value, null|array|string $groups = null): void;

    public function getViolations(mixed $value, null|array|string $groups = null): ConstraintViolationListInterface;
}
