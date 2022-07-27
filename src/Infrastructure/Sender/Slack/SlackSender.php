<?php

namespace App\Infrastructure\Sender\Slack;

use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Infrastructure\Sender\Interfaces\SenderInterface;
use App\Infrastructure\Sender\Slack\Service\SlackSenderService;
use Psr\Log\LoggerInterface;
use Throwable;

class SlackSender implements SenderInterface
{
    public function __construct(
        private readonly SlackSenderService $service,
        private readonly LoggerInterface $logger
    ) {
    }

    public function send(NotifyMessage $message): void
    {
        $notify = (string)$message->getBody()['message'];

        try {
            $this->service->send($notify);
        } catch (Throwable $exception) {
            $this->logger->error(
                'Error by sending slack',
                ['exception' => $exception->getMessage()]
            );
        }
    }
}