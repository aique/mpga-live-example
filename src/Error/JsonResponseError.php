<?php

namespace App\Error;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class JsonResponseError
{
    public function createResponseFromMessage(string $message): array {
        $response = [];

        $response['error']['message'] = $message;

        return $response;
    }

    public function createResponseFromViolationList(ConstraintViolationListInterface $violations): array {
        $response = [];

        $response['error']['message'] = 'Validation error';

        foreach ($violations as $violation) {
            if (!$violation instanceof ConstraintViolation) {
                continue;
            }

            $response['error']['violations'][] = sprintf(
                '%s: %s',
                $violation->getPropertyPath(),
                $violation->getMessage()
            );
        }

        return $response;
    }
}