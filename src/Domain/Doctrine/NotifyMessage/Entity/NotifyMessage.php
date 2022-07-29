<?php


namespace App\Domain\Doctrine\NotifyMessage\Entity;


use App\Application\Exception\NotFoundEntityException;
use App\Domain\Doctrine\Common\Traits\CreatedAt;
use App\Domain\Doctrine\Common\Traits\Entity;
use App\Domain\Doctrine\Common\Traits\UpdatedAt;
use App\Domain\Doctrine\NotifyMessage\Enum\NotifyStatusEnum;
use App\Domain\Doctrine\NotifyMessage\Enum\NotifyTypeEnum;
use App\Infrastructure\Doctrine\Interfaces\EntityInterface;
use Webmozart\Assert\Assert;

final class NotifyMessage implements EntityInterface
{
    use Entity, CreatedAt, UpdatedAt;

    public function __construct(
        private readonly string $type,
        private readonly array $message,
        private int $status,
    ) {
        $this->identify();
        $this->onCreated();
        $this->checkStatusType($status);
        $this->checkType($type);
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @throws NotFoundEntityException
     */
    public function getTextStatus(): string
    {
        $status = NotifyStatusEnum::tryFrom($this->status);

        if ($status === null) {
            throw new NotFoundEntityException('notify status type not found');
        }

        return $status->getTextStatus();
    }

    public function setStatus(int $status): void
    {
        $this->checkStatusType($status);

        $this->status = $status;
    }

    public function setUpdatedAt(): void
    {
        $this->onUpdated();
    }

    public function getBody(): array
    {
        return $this->message;
    }

    private function checkStatusType(int $status): void
    {
        Assert::inArray($status, NotifyStatusEnum::getStatusMap());
    }

    private function checkType(string $type): void
    {
        Assert::inArray($type, NotifyTypeEnum::getStatusMap());
    }
}
