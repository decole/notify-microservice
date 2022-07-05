<?php


namespace App\Infrastructure\Doctrine\Repository\NotifyMessage;


use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Infrastructure\Doctrine\Interfaces\NotifyMessageRepositoryInterface;
use App\Infrastructure\Doctrine\Repository\BaseDoctrineRepository;
use Doctrine\ORM\NonUniqueResultException;

final class NotifyMessageRepository extends BaseDoctrineRepository implements NotifyMessageRepositoryInterface
{
    /**
     * @throws NonUniqueResultException
     */
    public function findById(string $id): ?NotifyMessage
    {
        return $this->entityManager->createQueryBuilder()
            ->select('m')
            ->from(NotifyMessage::class, 'm')
            ->where('m.id = :value')
            ->setParameter('value', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}