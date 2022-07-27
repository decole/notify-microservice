<?php

namespace App\Infrastructure\Sender\Discord;

use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Infrastructure\Sender\Discord\Service\DiscordSenderService;
use App\Infrastructure\Sender\Interfaces\SenderInterface;
use Psr\Log\LoggerInterface;

class DiscordSender implements SenderInterface
{
    public function __construct(
        private readonly DiscordSenderService $service,
        private readonly LoggerInterface $logger
    ) {
    }

    public function send(NotifyMessage $message): void
    {
        $message = $message->getBody()['message'];

        try {
            $this->service->sendMessage($message);
        } catch (\Throwable $exception) {
            $this->logger->error(
                'Error by sending discord',
                [
                    'exception' => $exception->getMessage(),
                ]
            );
        }
    }
}
