<?php


namespace App\Infrastructure\Sender\Telegram\Service;


use App\Infrastructure\Sender\Telegram\Exception\TelegramServiceException;
use Psr\Log\LoggerInterface;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;

class TelegramSenderService
{
    private Api $telegram;

    /**
     * @throws TelegramServiceException
     * @throws TelegramSDKException
     */
    public function __construct(private readonly LoggerInterface $logger, private readonly ?string $apiToken = null)
    {
        if ($this->apiToken === null) {
            $this->logger->error('Telegram bot not configured, Api token is null');

            throw TelegramServiceException::apiTokenEmpty();
        }

        $this->telegram = new Api($apiToken);
    }

    public function sendMessage(mixed $chatId, mixed $notify): void
    {
        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $notify,
        ]);
    }
}