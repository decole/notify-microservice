<?php


namespace App\Application\EventListener;


use App\Application\Event\MessageStatusUpdatedEvent;
use App\Infrastructure\RabbitMq\Producer\History\HistoryMessageProducer;
use JsonException;

final class MessageStatusUpdatedListener
{
    public function __construct(
        private readonly HistoryMessageProducer $producer,
    ) {
    }

    /**
     * @throws JsonException
     */
    public function onMessageStatusUpdatedEvent(MessageStatusUpdatedEvent $event): void
    {
        $message = json_encode([
            'messageId' => $event->getMessage()->getId(),
            'status' => $event->getNewStatus(),
            'info' => $event->getInfo(),
        ], JSON_THROW_ON_ERROR);

        $this->producer->publish($message);
    }
}
