<?php


namespace App\Application\Service\Factory;


use App\Application\Exception\NotFoundEntityException;
use App\Application\Service\ExtendedInputInterface;
use App\Application\Service\ValidationCriteria\EmailValidationCriteria;
use App\Application\Service\ValidationCriteria\TelegramValidationCriteria;
use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;

final class ValidationFactory implements ValidationFactoryInterface
{
    /**
     * @throws NotFoundEntityException
     */
    public function getCriteria(ExtendedInputInterface $input): ValidationCriteriaInterface
    {
        return match ($input->type) {
            NotifyMessage::EMAIL_TYPE => new EmailValidationCriteria($input),
            NotifyMessage::TELEGRAM_TYPE => new TelegramValidationCriteria($input),

            default => throw new NotFoundEntityException('Validation criteria by notify type not found.'),
        };
    }
}