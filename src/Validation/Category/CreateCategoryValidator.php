<?php

namespace App\Validation\Category;

use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class CreateCategoryValidator extends AbstractValidator
{
    protected function getConstraints(): array {
        return [
            new Collection([
                'name' => [
                    new NotBlank()
                ],
                'enabled' => [
                    new Type('boolean')
                ]
            ]),
        ];
    }
}