<?php


namespace App\Infrastructure\Sender\Sms;


use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Infrastructure\Sender\Interfaces\SenderInterface;
use App\Infrastructure\Sender\Sms\Service\SmsProviderResolver;
use Psr\Log\LoggerInterface;
use Throwable;

class SmsSender implements SenderInterface
{
    public function __construct(
        private readonly SmsProviderResolver $service,
        private readonly LoggerInterface $logger
    ) {
    }

    public function send(NotifyMessage $message): void
    {
        $body = $message->getBody();
        $phone = $body['phone'];
        $notify = $body['message'];

        try {
            $result = $this->service->send($phone, $notify);

            $this->logger->info(
                'Success sending sms',
                [
                    'phone' => $phone,
                    'message' => $notify,
                    'result' => (string)$result,
                ]
            );
        } catch (Throwable $exception) {
            $this->logger->error(
                'Error by sending sms',
                [
                    'exception' => $exception->getMessage(),
                ]
            );
        }
    }
}