<?php

namespace App\Infrastructure\RabbitMq\Consumer\History;

use App\Domain\Doctrine\HistoryNotification\Entity\HistoryNotification;
use App\Infrastructure\Doctrine\Service\HistoryNotificationService;
use App\Infrastructure\Doctrine\Service\NotifyMessageService;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class HistoryMessageConsumer implements ConsumerInterface
{
    public function __construct(
        private HistoryNotificationService $historyNotificationService,
        private NotifyMessageService $notifyMessageService,
    ) {
    }

    public function execute(AMQPMessage $msg): bool|int
    {
        $body = json_decode($msg->body, true);
        $message = $this->notifyMessageService->find((string)$body['messageId']);

        if ($message === null) {
            return ConsumerInterface::MSG_REJECT;
        }

        $this->historyNotificationService->create(new HistoryNotification(
            message: $message,
            status: (int)$body['status'],
            info: (string)$body['info']
        ));

        return ConsumerInterface::MSG_ACK;
    }
}
