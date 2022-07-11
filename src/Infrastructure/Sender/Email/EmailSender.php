<?php


namespace App\Infrastructure\Sender\Email;


use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Infrastructure\Sender\Interfaces\SenderInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class EmailSender implements SenderInterface
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly ?string $emailSubject = null,
        private readonly ?string $emailFrom = null,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function send(NotifyMessage $message): void
    {
        [$emailTo, $notify] = $this->getEmailNotifyParams($message);

        $email = (new Email())
            ->from($this->emailFrom)
            ->to($emailTo)
            ->subject($this->emailSubject)
            ->text($notify);

        $this->mailer->send($email);
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