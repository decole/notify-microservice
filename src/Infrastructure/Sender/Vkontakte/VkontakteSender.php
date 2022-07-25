<?php


namespace App\Infrastructure\Sender\Vkontakte;


use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Infrastructure\Sender\Interfaces\SenderInterface;
use App\Infrastructure\Sender\Vkontakte\Service\VkontakteSenderService;
use Psr\Log\LoggerInterface;
use Throwable;

class VkontakteSender implements SenderInterface
{
    public function __construct(
        private readonly VkontakteSenderService $service,
        private readonly LoggerInterface $logger
    ) {
    }

    public function send(NotifyMessage $message): void
    {
        $notify = $message->getBody()['message'];

        try {
            $this->service->send($notify);
        } catch (Throwable $exception) {
            $this->logger->error(
                'Error by sending vkontakte',
                [
                    'exception' => $exception->getMessage(),
                ]
            );
        }
    }
}