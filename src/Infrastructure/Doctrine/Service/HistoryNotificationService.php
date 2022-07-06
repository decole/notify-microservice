<?php


namespace App\Infrastructure\Doctrine\Service;


use App\Domain\Doctrine\Common\Transactions\TransactionInterface;
use App\Domain\Doctrine\HistoryNotification\Entity\HistoryNotification;
use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Infrastructure\Doctrine\Interfaces\HistoryNotificationRepositoryInterface;

final class HistoryNotificationService
{
    public function __construct(
        private readonly HistoryNotificationRepositoryInterface $repository,
        private readonly TransactionInterface $transaction,
    ) {
    }

    public function create(HistoryNotification $history): HistoryNotification
    {
        $this->transaction->transactional(function () use ($history) {
            $this->repository->save($history);
        });

        return $history;
    }

    public function findByNotifyMessage(NotifyMessage $notifyMessage): array
    {
        return $this->repository->findByNotifyMessage((string)$notifyMessage->getId());
    }
}