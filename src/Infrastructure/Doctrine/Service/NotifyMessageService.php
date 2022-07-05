<?php


namespace App\Infrastructure\Doctrine\Service;


use App\Application\Http\Api\SingleNotify\Dto\MessageDto;
use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Infrastructure\Doctrine\Interfaces\NotifyMessageRepositoryInterface;

final class NotifyMessageService
{
    public function __construct(private readonly NotifyMessageRepositoryInterface $repository)
    {
    }

    /**
     * @param MessageDto $dto
     * @return NotifyMessage
     * @throws \Doctrine\ORM\Exception\ORMException|\Doctrine\ORM\OptimisticLockException
     */
    public function create(MessageDto $dto): NotifyMessage
    {
        $message = $this->getNewEntityByDto($dto);


        return $this->repository->save($message);
    }

    private function getNewEntityByDto(MessageDto $dto): NotifyMessage
    {
        return new NotifyMessage($dto->getType(), $dto->getMessage(), $dto->getStatus());
    }

    public function find(string $id): ?NotifyMessage
    {
        return $this->repository->findById($id);
    }

    public function updateStatus(string $id, int $status): NotifyMessage
    {
        $message = $this->find($id);

        assert($message instanceof NotifyMessage);

        $message->setStatus($status);
        $message->setUpdatedAt();

        return $this->repository->save($message);
    }
}