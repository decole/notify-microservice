<?php


namespace App\Application\Service\Factory;


use App\Application\Exception\NotFoundEntityException;
use App\Application\Http\Api\SingleNotify\Input\MessageInput;
use App\Application\Service\ValidationCriteria\EmailValidationCriteria;

final class ValidationFactory implements ValidationFactoryInterface
{
    /**
     * @throws NotFoundEntityException
     */
    public function getCriteria(MessageInput $input): ValidationCriteriaInterface
    {
        return match ($input->type) {
            'email' => new EmailValidationCriteria($input),
            default => throw new NotFoundEntityException('Validation criteria by notify type not found.'),
        };
    }
}