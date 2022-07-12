<?php


namespace App\Application\Factory\ProducerFactory;


use App\Application\Exception\NotFoundEntityException;
use App\Infrastructure\RabbitMq\Producer\Email\EmailProducer;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;

final class NotifyProducerFactory
{
    public function __construct(
        private readonly EmailProducer $emailProducer
    ) {
    }

    /**
     * @throws NotFoundEntityException
     */
    public function createProducer(string $type): ProducerInterface
    {
        return match ($type) {
            'email' => $this->emailProducer,
            default => throw new NotFoundEntityException('Cant create notify send producer'),
        };
    }
}