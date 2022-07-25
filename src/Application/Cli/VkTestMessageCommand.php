<?php


namespace App\Application\Cli;


use App\Infrastructure\Sender\Vkontakte\Service\VkontakteSenderService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'cli:vk:test', description: 'Команда отладка отправки сообщений в чат вашего сообщества')]
class VkTestMessageCommand extends Command
{
    public function __construct(private readonly VkontakteSenderService $service)
    {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        dump('If is ok - return number of sended message');
        dump($this->service->send('test'));

        return 0;
    }
}