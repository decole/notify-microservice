<?php


namespace App\Infrastructure\RabbitMq\Consumer\Vkontakte;


use App\Infrastructure\Doctrine\Service\NotifyMessageService;
use App\Infrastructure\RabbitMq\Consumer\AbstractNotifyConsumer;
use App\Infrastructure\Sender\Interfaces\SenderInterface;
use App\Infrastructure\Sender\Vkontakte\VkontakteSender;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;

class VkontakteConsumer extends AbstractNotifyConsumer implements ConsumerInterface
{
    public function __construct(
        private readonly VkontakteSender $sender,
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