<?php


namespace App\Infrastructure\Doctrine\Repository\HistoryNotification;


use App\Domain\Doctrine\HistoryNotification\Entity\HistoryNotification;
use App\Infrastructure\Doctrine\Interfaces\HistoryNotificationRepositoryInterface;
use App\Infrastructure\Doctrine\Repository\BaseDoctrineRepository;
use Doctrine\ORM\NonUniqueResultException;

final class HistoryNotificationRepository extends BaseDoctrineRepository implements HistoryNotificationRepositoryInterface
{
    /**
    * @throws NonUniqueResultException
    */
    public function findById(string $id): ?HistoryNotification
    {
        return $this->entityManager->createQueryBuilder()
            ->select('h')
            ->from(HistoryNotification::class, 'h')
            ->where('h.id = :value')
            ->setParameter('value', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByNotifyMessage(string $id): array
    {
        return $this->entityManager->createQueryBuilder()
            ->select('h')
            ->from(HistoryNotification::class, 'h')
            ->where('h.message = :value')
            ->setParameter('value', $id)
            ->orderBy('h.createdAt', 'DESC')
            ->getQuery()
            ->getArrayResult();
    }
}