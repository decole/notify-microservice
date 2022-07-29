<?php


namespace App\Infrastructure\Cli;


use App\Infrastructure\Sender\Telegram\Service\TelegramSenderService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'cli:telegram:test', description: 'Команда отладка отправки сообщений в телеграм чат. Найти ваш id - https://t.me/username_to_id_bot')]
class TelegramTestMessageCommand extends Command
{
    public function __construct(private readonly TelegramSenderService $service)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('userId', InputArgument::REQUIRED, 'Your userId of telegram? https://t.me/username_to_id_bot')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $chatId = $input->getArgument('userId');
        $this->service->sendMessage($chatId, 'test');

        dump('Check your telegram client');

        return 0;
    }
}