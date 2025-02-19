<?php

namespace App\Service\Validator\Http;

use App\Exception\ValidationFailedException;
use App\Service\Validator\ValidatorInterface as BaseValidatorInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

final readonly class Validator implements ValidatorInterface
{
    public function __construct(private BaseValidatorInterface $validator) {}

    public function validate(mixed $value, null|array|string $groups = null): void
    {
        $violations = $this->validator->getViolations($value, $groups);

        if ($violations->count() > 0) {
            throw new HttpException(422, 'Invalid data provided.', new ValidationFailedException($value, $violations));
        }
    }
}
