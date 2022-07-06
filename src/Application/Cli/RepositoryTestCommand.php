<?php


namespace App\Application\Cli;


use App\Application\Exception\NotFoundEntityException;
use App\Application\Http\Api\SingleNotify\Dto\MessageDto;
use App\Domain\Doctrine\HistoryNotification\Entity\HistoryNotification;
use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Infrastructure\Doctrine\Service\HistoryNotificationService;
use App\Infrastructure\Doctrine\Service\NotifyMessageService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @todo Удадить после того, как реализуется хотя бы 2 воркера
 */
#[AsCommand(name: 'cli:test-repo', description: 'Тестовая консольная команда проверки работоспособности репозиториев')]
class RepositoryTestCommand extends Command
{
    public function __construct(
        private readonly NotifyMessageService $service,
        private readonly HistoryNotificationService $historyService,
    ) {
        parent::__construct();
    }

    /**
     * @throws NotFoundEntityException
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $dto = new MessageDto(
            type: 'email',
            status: NotifyMessage::STATUS_IN_QUEUE,
            message: ['example' => 'lol']
        );

        dump('check save NotifyMessage Entity');

        $entity = $this->service->create($dto);

        dump($entity);

        $findEntity = $this->service->find($entity->getId()->toString());

        dump((string)$findEntity->getId());

        $updateStatus = $this->service->updateStatus((string)$findEntity->getId(), NotifyMessage::STATUS_ACTIVE);

        dump($updateStatus->getStatus());

        dump('check save HistoryNotifyMessage Entity');

        $history = new HistoryNotification($findEntity, NotifyMessage::STATUS_ERROR, 'exception text');

        $this->historyService->create($history);

        $history = new HistoryNotification($findEntity, NotifyMessage::STATUS_DONE);

        $this->historyService->create($history);

        dump('history events saved');

        dump('check find notify events');

        assert($findEntity instanceof NotifyMessage);

        $collection = $this->historyService->findByNotifyMessage($findEntity);

        dump($collection);

        dump('get last notify history state');

        $lastNotifyState = $this->service->getNotifyLastStatus((string)$findEntity->getId());

        dump('last status:', $lastNotifyState);

        return 0;
    }
}