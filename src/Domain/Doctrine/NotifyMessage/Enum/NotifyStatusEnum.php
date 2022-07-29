<?php


namespace App\Domain\Doctrine\NotifyMessage\Enum;


enum NotifyStatusEnum: int
{
    case IN_QUEUE = 0;
    case ACTIVE = 1;
    case ERROR = 2;
    case DONE = 3;

    public static function getStatusMap(): array
    {
        return [
            self::IN_QUEUE->value,
            self::ACTIVE->value,
            self::ERROR->value,
            self::DONE->value,
        ];
    }

    public function getTextStatus(): string
    {
        return match ($this->name) {
            self::IN_QUEUE->name => 'in queue',
            self::ACTIVE->name => 'sending',
            self::ERROR->name => 'error',
            self::DONE->name => 'sent',
        };
    }
}