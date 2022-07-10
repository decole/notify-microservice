<?php

namespace App\Application\Event;

use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use Symfony\Contracts\EventDispatcher\Event;

class MessageStatusUpdatedEvent extends Event
{
    public const NAME = 'message_status_updated_event';

    public function __construct(
        private NotifyMessage $message,
        private string $info = '',
    ) {
    }

    public function getMessage(): NotifyMessage
    {
        return $this->message;
    }

    public function getInfo(): string
    {
        return $this->info;
    }
}
