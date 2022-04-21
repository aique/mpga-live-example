<?php

namespace App\Error;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class JsonResponseError
{
    public function createResponseFromViolationList(ConstraintViolationListInterface $violations): array {
        $response = [];

        foreach ($violations as $violation) {
            if (!$violation instanceof ConstraintViolation) {
                continue;
            }

            $response['error'][] = sprintf(
                '%s: %s',
                $violation->getPropertyPath(),
                $violation->getMessage()
            );
        }

        return $response;
    }
}