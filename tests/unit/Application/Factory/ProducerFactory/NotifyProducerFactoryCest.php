<?php


namespace App\Tests\unit\Application\Factory\ProducerFactory;


use App\Application\Exception\NotFoundEntityException;
use App\Application\Factory\ProducerFactory\NotifyProducerFactory;
use App\Infrastructure\RabbitMq\Producer\Discord\DiscordProducer;
use App\Infrastructure\RabbitMq\Producer\Email\EmailProducer;
use App\Infrastructure\RabbitMq\Producer\Slack\SlackProducer;
use App\Infrastructure\RabbitMq\Producer\Sms\SmsProducer;
use App\Infrastructure\RabbitMq\Producer\Telegram\TelegramProducer;
use App\Infrastructure\RabbitMq\Producer\Vkontakte\VkontakteProducer;
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

    public function createTelegramProducer(UnitTester $I): void
    {
        /** @var NotifyProducerFactory $service */
        $service = $I->grabService(NotifyProducerFactory::class);

        $producer = $service->createProducer('telegram');

        $I->assertInstanceOf(TelegramProducer::class, $producer);
    }

    public function createVkontakteProducer(UnitTester $I): void
    {
        /** @var NotifyProducerFactory $service */
        $service = $I->grabService(NotifyProducerFactory::class);

        $producer = $service->createProducer('vk');

        $I->assertInstanceOf(VkontakteProducer::class, $producer);
    }

    public function createSlackProducer(UnitTester $I): void
    {
        /** @var NotifyProducerFactory $service */
        $service = $I->grabService(NotifyProducerFactory::class);

        $producer = $service->createProducer('slack');

        $I->assertInstanceOf(SlackProducer::class, $producer);
    }

    public function createSmsProducer(UnitTester $I): void
    {
        /** @var NotifyProducerFactory $service */
        $service = $I->grabService(NotifyProducerFactory::class);

        $producer = $service->createProducer('sms');

        $I->assertInstanceOf(SmsProducer::class, $producer);
    }

    public function createDiscordProducer(UnitTester $I): void
    {
        /** @var NotifyProducerFactory $service */
        $service = $I->grabService(NotifyProducerFactory::class);

        $producer = $service->createProducer('discord');

        $I->assertInstanceOf(DiscordProducer::class, $producer);
    }

    public function negativeCreateProducer(UnitTester $I): void
    {
        /** @var NotifyProducerFactory $service */
        $service = $I->grabService(NotifyProducerFactory::class);

        $I->expectThrowable(
            new NotFoundEntityException('Can`t create notify send producer'),
            function () use ($service) {
                $service->createProducer('error');
            }
        );
    }
}
