<?php

namespace App\Validation\Category;

use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractValidator
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate(array $data): ConstraintViolationListInterface {
        return $this->validator->validate(
            $data, $this->getConstraints()
        );
    }

    protected abstract function getConstraints(): array;
}