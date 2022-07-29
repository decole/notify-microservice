<?php


namespace App\Infrastructure\Cli;


use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Domain\Doctrine\NotifyMessage\Enum\NotifyStatusEnum;
use App\Domain\Doctrine\NotifyMessage\Enum\NotifyTypeEnum;
use App\Infrastructure\Sender\Email\EmailSender;
use ContainerAJZyK86\getEmailSenderService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'cli:email:test', description: 'Команда отладка отправки сообщений на email.')]
class EmailTestMessageCommand extends Command
{
    public function __construct(private readonly EmailSender $service)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'enter email for sending test notify')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getArgument('email');
        $this->service->send($this->getMassage($email));

        dump('Check your email client');

        return 0;
    }

    private function getMassage(string $email): NotifyMessage
    {
        return new NotifyMessage(
            type: NotifyTypeEnum::EMAIL->value,
            message: [
                'email' => $email,
                'message' => 'test email message by notify microservice',
            ],
            status: NotifyStatusEnum::IN_QUEUE->value
        );
    }
}