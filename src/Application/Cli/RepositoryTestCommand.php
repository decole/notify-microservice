<?php


namespace App\Application\Cli;


use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'cli:test', description: 'Тестовая консольная команда')]
class RepositoryTestCommand extends Command
{
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        dump('test cli command');

        return 0;
    }
}