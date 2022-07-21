<?php


namespace App\Infrastructure\Sender\Telegram;


use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Infrastructure\Sender\Interfaces\SenderInterface;
use App\Infrastructure\Sender\Telegram\Service\TelegramSenderService;
use Psr\Log\LoggerInterface;

class TelegramSender implements SenderInterface
{
    public function __construct(
        private readonly TelegramSenderService $service,
        private readonly LoggerInterface $logger
    ) {
    }

    public function send(NotifyMessage $message): void
    {
        $body = $message->getBody();
        $chatId = $body['userId'];
        $notify = $body['message'];

        try {
            $this->service->sendMessage($chatId, $notify);
        } catch (\Throwable $exception) {
            $this->logger->error(
                'Error by sending telegram',
                [
                    'exception' => $exception->getMessage(),
                ]
            );
        }
    }
}