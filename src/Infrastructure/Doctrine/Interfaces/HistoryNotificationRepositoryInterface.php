<?php


namespace App\Infrastructure\Doctrine\Interfaces;


use App\Domain\Doctrine\HistoryNotification\Entity\HistoryNotification;

interface HistoryNotificationRepositoryInterface extends EntityInterface
{
    public function findById(string $id): ?HistoryNotification;

    public function findByNotifyMessage(string $id): array;
}