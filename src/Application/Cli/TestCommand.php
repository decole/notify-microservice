<?php


namespace App\Application\Cli;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends Command
{
    protected static $defaultName = 'cli:test';

    public function configure(): void
    {
        $this->setDescription('Тестовая консольная команда');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        dump('test');

        return 0;
    }
}