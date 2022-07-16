<?php


namespace App\Tests\unit\Application\Factory\ProducerFactory;


use App\Application\Exception\NotFoundEntityException;
use App\Application\Factory\ProducerFactory\NotifyProducerFactory;
use App\Infrastructure\RabbitMq\Producer\Email\EmailProducer;
use App\Tests\UnitTester;

class NotifyProducerFactoryCest
{
    public function createEmailProducer(UnitTester $I): void
    {
        /** @var NotifyProducerFactory $service */
        $service = $I->grabService(NotifyProducerFactory::class);

        $producer = $service->createProducer('email');

        $I->assertInstanceOf(EmailProducer::class, $producer);
    }

    public function negativeCreateProducer(UnitTester $I): void
    {
        /** @var NotifyProducerFactory $service */
        $service = $I->grabService(NotifyProducerFactory::class);

        $I->expectThrowable(
            new NotFoundEntityException('Cant create notify send producer'),
            function () use ($service) {
                $service->createProducer('error');
            }
        );
    }
}