<?php


namespace App\Application\Service\Factory;


use App\Application\Exception\NotFoundEntityException;
use App\Application\Service\ExtendedInputInterface;
use App\Application\Service\ValidationCriteria\EmailValidationCriteria;
use App\Application\Service\ValidationCriteria\TelegramValidationCriteria;
use App\Application\Service\ValidationCriteria\VkontakteValidationCriteria;
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
            NotifyMessage::VKONTAKTE_TYPE => new VkontakteValidationCriteria($input),

            default => throw new NotFoundEntityException('Validation criteria by notify type not found.'),
        };
    }
}