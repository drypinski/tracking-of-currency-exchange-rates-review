<?php

namespace App\Service\Validator;

use Symfony\Component\Validator\ConstraintViolationList as SymfonyConstraintViolationList;

final class ConstraintViolationList extends SymfonyConstraintViolationList implements ConstraintViolationListInterface {}
