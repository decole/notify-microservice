<?php


namespace App\Domain\Doctrine\NotifyMessage\Enum;


enum NotifyTypeEnum: string
{
    case EMAIL = 'email';
    case TELEGRAM = 'telegram';
    case VKONTAKTE = 'vk';
    case SLACK = 'slack';
    case SMS = 'sms';
    case DISCORD = 'discord';

    public static function getStatusMap(): array
    {
        return [
            self::EMAIL->value,
            self::TELEGRAM->value,
            self::VKONTAKTE->value,
            self::SLACK->value,
            self::SMS->value,
            self::DISCORD->value,
        ];
    }
}