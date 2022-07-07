<?php


namespace App\Application\Factory\ProducerFactory;


use App\Infrastructure\RabbitMq\Producer\Email\EmailProducer;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;

final class NotifyProducerFactory
{
    public function __construct(
        private readonly EmailProducer $emailProducer
    ) {
    }

    public function createProducer(string $type): ProducerInterface
    {
        return match ($type) {
            'email' => $this->emailProducer,
        };
    }
}