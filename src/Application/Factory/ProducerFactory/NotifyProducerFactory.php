<?php


namespace App\Application\Factory\ProducerFactory;


use App\Application\Exception\NotFoundEntityException;
use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
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
            NotifyMessage::EMAIL_TYPE => $this->emailProducer,
            NotifyMessage::TELEGRAM_TYPE => $this->telegramProducer,
            NotifyMessage::VKONTAKTE_TYPE => $this->vkontakteProducer,
            NotifyMessage::SLACK_TYPE => $this->slackProducer,
            NotifyMessage::SMS_TYPE => $this->smsProducer,
            NotifyMessage::DISCORD_TYPE => $this->discordProducer,

            default => throw new NotFoundEntityException('Can`t create notify send producer'),
        };
    }
}
