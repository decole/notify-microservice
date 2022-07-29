<?php


namespace App\Application\Factory\ProducerFactory;


use App\Application\Exception\NotFoundEntityException;
use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Domain\Doctrine\NotifyMessage\Enum\NotifyTypeEnum;
use App\Infrastructure\RabbitMq\Producer\Discord\DiscordProducer;
use App\Infrastructure\RabbitMq\Producer\Email\EmailProducer;
use App\Infrastructure\RabbitMq\Producer\Slack\SlackProducer;
use App\Infrastructure\RabbitMq\Producer\Sms\SmsProducer;
use App\Infrastructure\RabbitMq\Producer\Telegram\TelegramProducer;
use App\Infrastructure\RabbitMq\Producer\Vkontakte\VkontakteProducer;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;

final class NotifyProducerFactory
{
    public function __construct(
        private readonly EmailProducer $emailProducer,
        private readonly TelegramProducer $telegramProducer,
        private readonly SlackProducer $slackProducer,
        private readonly VkontakteProducer $vkontakteProducer,
        private readonly SmsProducer $smsProducer,
        private readonly DiscordProducer $discordProducer,
    ) {
    }

    /**
     * @throws NotFoundEntityException
     */
    public function createProducer(string $type): ProducerInterface
    {
        return match ($type) {
            NotifyTypeEnum::EMAIL->value => $this->emailProducer,
            NotifyTypeEnum::TELEGRAM->value => $this->telegramProducer,
            NotifyTypeEnum::VKONTAKTE->value => $this->vkontakteProducer,
            NotifyTypeEnum::SLACK->value => $this->slackProducer,
            NotifyTypeEnum::SMS->value => $this->smsProducer,
            NotifyTypeEnum::DISCORD->value => $this->discordProducer,

            default => throw new NotFoundEntityException('Can`t create notify send producer'),
        };
    }
}
