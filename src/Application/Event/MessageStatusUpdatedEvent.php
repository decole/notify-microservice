<?php


namespace App\Application\Event;


use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use Symfony\Contracts\EventDispatcher\Event;

final class MessageStatusUpdatedEvent extends Event
{
    public const NAME = 'message_status_updated_event';

    public function __construct(
        private readonly NotifyMessage $message,
        private readonly int $newStatus,
        private readonly ?string $info = null,
    ) {
    }

    public function getMessage(): NotifyMessage
    {
        return $this->message;
    }

    public function getNewStatus(): int
    {
        return $this->newStatus;
    }

    public function getInfo(): ?string
    {
        return $this->info;
    }
}
