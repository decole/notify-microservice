<?php


namespace App\Domain\Doctrine\NotifyMessage\Entity;


use App\Domain\Doctrine\Common\Traits\CreatedAt;
use App\Domain\Doctrine\Common\Traits\Entity;
use App\Domain\Doctrine\Common\Traits\UpdatedAt;
use App\Infrastructure\Doctrine\Interfaces\EntityInterface;
use Webmozart\Assert\Assert;

final class NotifyMessage implements EntityInterface
{
    public const STATUS_IN_QUEUE = 0;
    public const STATUS_ACTIVE = 1;
    public const STATUS_ERROR = 2;

    public const STATUS_MAP = [
        self::STATUS_IN_QUEUE,
        self::STATUS_ACTIVE,
        self::STATUS_ERROR,
    ];

    public const EMAIL_TYPE = 'email';

    public const TYPE_MAP = [
        self::EMAIL_TYPE,
    ];

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

    public function setUpdatedAt(): void
    {
        $this->onUpdated();
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->checkStatusType($status);

        $this->status = $status;
    }

    private function checkStatusType(int $status): void
    {
        Assert::inArray($status, self::STATUS_MAP);
    }

    private function checkType(string $type): void
    {
        Assert::inArray($type, self::TYPE_MAP);
    }
}