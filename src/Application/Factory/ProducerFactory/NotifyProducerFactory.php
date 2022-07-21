<?php


namespace App\Application\Factory\ProducerFactory;


use App\Application\Exception\NotFoundEntityException;
use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Infrastructure\RabbitMq\Producer\Email\EmailProducer;
use App\Infrastructure\RabbitMq\Producer\Telegram\TelegramProducer;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;

final class NotifyProducerFactory
{
    public function __construct(
        private readonly EmailProducer $emailProducer,
        private readonly TelegramProducer $telegramProducer
    ) {
    }

    /**
     * @throws NotFoundEntityException
     */
    public function createProducer(string $type): ProducerInterface
    {
        return match ($type) {
            NotifyMessage::EMAIL_TYPE => $this->emailProducer,
            NotifyMessage::TELEGRAM_TYPE => $this->telegramProducer,

            default => throw new NotFoundEntityException('Cant create notify send producer'),
        };
    }
}