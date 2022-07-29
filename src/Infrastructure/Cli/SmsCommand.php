<?php


namespace App\Infrastructure\Cli;


use App\Infrastructure\Sender\Sms\Service\SmsProviderResolver;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'cli:sms:test', description: 'Команда отладка отправки sms сообщения')]
class SmsCommand extends Command
{
    public function __construct(private readonly SmsProviderResolver $service)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('phone', InputArgument::REQUIRED, 'Enter you phone')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $phone = $input->getArgument('phone');

        dump("Try send sms for phone {$phone}");

        $t = $this->service->send($phone, 'test notify');

        dump((string)$t);

        return 0;
    }
}