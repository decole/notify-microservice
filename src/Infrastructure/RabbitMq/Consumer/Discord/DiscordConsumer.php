<?php

namespace App\Infrastructure\RabbitMq\Consumer\Discord;

use App\Infrastructure\Doctrine\Service\NotifyMessageService;
use App\Infrastructure\RabbitMq\Consumer\AbstractNotifyConsumer;
use App\Infrastructure\Sender\Discord\DiscordSender;
use App\Infrastructure\Sender\Interfaces\SenderInterface;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;

class DiscordConsumer extends AbstractNotifyConsumer implements ConsumerInterface
{
    public function __construct(
        private readonly DiscordSender $sender,
        private readonly NotifyMessageService $service,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly LoggerInterface $logger
    ) {
        parent::__construct(
            service: $this->service,
            eventDispatcher: $this->eventDispatcher,
            logger: $this->logger,
        );
    }

    public function getSender(): SenderInterface
    {
        return $this->sender;
    }

}
