<?php


namespace App\Infrastructure\Sender\Email;


use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Infrastructure\Sender\Interfaces\SenderInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailSender implements SenderInterface
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly LoggerInterface $logger,
        private readonly ?string $emailSubject = null,
        private readonly ?string $emailFrom = null
    ) {
    }

    public function send(NotifyMessage $message): void
    {
        [$emailTo, $notify] = $this->getEmailNotifyParams($message);

        $email = (new Email())
            ->from($this->emailFrom)
            ->to($emailTo)
            ->subject($this->emailSubject)
            ->text($notify);

        try {
            $this->mailer->send($email);
        } catch (\Throwable $exception) {
            $this->logger->error(
                'Error by sending email',
                [
                    'exception' => $exception->getMessage(),
                ]
            );
        }
    }

    private function getEmailNotifyParams(NotifyMessage $message): array
    {
        $body = $message->getBody();

        return [
            $body['email'],
            $body['message'],
        ];
    }
}