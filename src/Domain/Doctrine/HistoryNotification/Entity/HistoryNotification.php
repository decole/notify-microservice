<?php


namespace App\Domain\Doctrine\HistoryNotification\Entity;


use App\Domain\Doctrine\Common\Traits\CreatedAt;
use App\Domain\Doctrine\Common\Traits\Entity;
use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Domain\Doctrine\NotifyMessage\Enum\NotifyStatusEnum;
use App\Infrastructure\Doctrine\Interfaces\EntityInterface;
use Webmozart\Assert\Assert;

final class HistoryNotification implements EntityInterface
{
    use Entity, CreatedAt;

    public function __construct(
        private readonly NotifyMessage $message,
        private int $status,
        private ?string $info = null,
    ) {
        $this->identify();
        $this->onCreated();
        $this->checkStatusType($status);
    }

    private function checkStatusType(int $status): void
    {
        Assert::inArray($status, NotifyStatusEnum::getStatusMap());
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
        $this->checkStatusType($status);
    }

    public function getInfo(): ?string
    {
        return $this->info;
    }

    public function setInfo(?string $info): void
    {
        $this->info = $info;
    }
}