<?php


namespace App\Application\Factory\ProducerFactory;


final class NotifyProducerFactory
{
    public function createProducer(string $type): ProducerInterface
    {
        return match ($type) {
            'email' => new EmailProducer(),
        };
    }
}