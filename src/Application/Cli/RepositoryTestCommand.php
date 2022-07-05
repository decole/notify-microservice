<?php


namespace App\Application\Cli;


use App\Application\Http\Api\SingleNotify\Dto\MessageDto;
use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Infrastructure\Doctrine\Service\NotifyMessageService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RepositoryTestCommand extends Command
{
    protected static $defaultName = 'cli:test-repo';

    public function __construct(private readonly NotifyMessageService $service)
    {
        parent::__construct();
    }

    public function configure(): void
    {
        $this->setDescription('Тестовая консольная команда проверки работоспособности репозиториев');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $dto = new MessageDto(
            type: 'email',
            status: NotifyMessage::STATUS_IN_QUEUE,
            message: ['example' => 'lol']
        );

        $entity = $this->service->create($dto);

        dump($entity);

        $findEntity = $this->service->find($entity->getId()->toString());

        dump($findEntity);

        $updateStatus = $this->service->updateStatus((string)$findEntity->getId(), NotifyMessage::STATUS_ACTIVE);

        dump($updateStatus->getStatus());

        return 0;
    }
}