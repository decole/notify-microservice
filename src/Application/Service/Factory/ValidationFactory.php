<?php


namespace App\Application\Service\Factory;


use App\Application\Exception\NotFoundEntityException;
use App\Application\Service\ExtendedInputInterface;
use App\Application\Service\ValidationCriteria\DiscordValidationCriteria;
use App\Application\Service\ValidationCriteria\EmailValidationCriteria;
use App\Application\Service\ValidationCriteria\SlackValidationCriteria;
use App\Application\Service\ValidationCriteria\SmsValidationCriteria;
use App\Application\Service\ValidationCriteria\TelegramValidationCriteria;
use App\Application\Service\ValidationCriteria\VkontakteValidationCriteria;
use App\Domain\Doctrine\NotifyMessage\Enum\NotifyTypeEnum;

final class ValidationFactory implements ValidationFactoryInterface
{
    /**
     * @throws NotFoundEntityException
     */
    public function getCriteria(ExtendedInputInterface $input): ValidationCriteriaInterface
    {
        return match ($input->type) {
            NotifyTypeEnum::EMAIL->value => new EmailValidationCriteria($input),
            NotifyTypeEnum::TELEGRAM->value => new TelegramValidationCriteria($input),
            NotifyTypeEnum::VKONTAKTE->value => new VkontakteValidationCriteria($input),
            NotifyTypeEnum::SLACK->value => new SlackValidationCriteria($input),
            NotifyTypeEnum::SMS->value => new SmsValidationCriteria($input),
            NotifyTypeEnum::DISCORD->value => new DiscordValidationCriteria($input),

            default => throw new NotFoundEntityException('Validation criteria by notify type not found.'),
        };
    }
}
