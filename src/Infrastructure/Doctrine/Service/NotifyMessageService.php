<?php


namespace App\Infrastructure\Doctrine\Service;


use App\Application\Exception\NotFoundEntityException;
use App\Application\Http\Api\SingleNotify\Dto\MessageDto;
use App\Domain\Doctrine\Common\Transactions\TransactionInterface;
use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Infrastructure\Doctrine\Interfaces\NotifyMessageRepositoryInterface;

final class NotifyMessageService
{
    public function __construct(
        private readonly NotifyMessageRepositoryInterface $repository,
        private readonly TransactionInterface $transaction,
    ) {
    }

    /**
     * @param MessageDto $dto
     * @return NotifyMessage
     */
    public function create(MessageDto $dto): NotifyMessage
    {
        $message = $this->getNewEntityByDto($dto);

        $this->transaction->transactional(function () use ($message) {
            $this->repository->save($message);
        });

        return $message;
    }

    public function find(string $id): ?NotifyMessage
    {
        return $this->repository->findById($id);
    }

    public function update(NotifyMessage $notify): NotifyMessage
    {
        assert($notify instanceof NotifyMessage);

        $notify->setUpdatedAt();

        $this->transaction->transactional(function () use ($notify) {
            $this->repository->save($notify);
        });

        return $notify;
    }

    public function updateStatus(NotifyMessage $message, int $status): NotifyMessage
    {
        $message->setStatus($status);

        $message->setUpdatedAt();

        $this->transaction->transactional(function () use ($message) {
            $this->repository->save($message);
        });

        return $message;
    }

    private function getNewEntityByDto(MessageDto $dto): NotifyMessage
    {
        return new NotifyMessage($dto->getType(), $dto->getMessage(), NotifyMessage::STATUS_IN_QUEUE);
    }
}