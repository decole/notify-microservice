<?php


namespace App\Infrastructure\Doctrine\Interfaces;


use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;

interface NotifyMessageRepositoryInterface extends EntityInterface
{
    public function findById(string $id): ?NotifyMessage;
}