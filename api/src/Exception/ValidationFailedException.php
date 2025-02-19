<?php

namespace App\Exception;

use Symfony\Component\DependencyInjection\Attribute\Exclude;
use Symfony\Component\Validator\Exception\ValidationFailedException as SymfonyValidationFailedException;

#[Exclude]
class ValidationFailedException extends SymfonyValidationFailedException {}
