<?php

namespace App\Service\Validator;

use App\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface as SymfonyValidatorInterface;

final readonly class Validator implements ValidatorInterface
{
    public function __construct(private SymfonyValidatorInterface $validator) {}

    public function validate(mixed $value, null|array|string $groups = null): void
    {
        $violations = $this->validator->validate($value, null, $groups);

        if ($violations->count() > 0) {
            throw new ValidationFailedException($value, $violations);
        }
    }

    public function getViolations(mixed $value, null|array|string $groups = null): ConstraintViolationListInterface
    {
        $violationList = $this->validator->validate($value, null, $groups);

        return new ConstraintViolationList($violationList);
    }
}
