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
    public const STATUS_DONE = 3;

    public const STATUS_MAP = [
        self::STATUS_IN_QUEUE,
        self::STATUS_ACTIVE,
        self::STATUS_ERROR,
        self::STATUS_DONE,
    ];

    public const TEXT_STATUS_MAP = [
        self::STATUS_IN_QUEUE => 'in queue',
        self::STATUS_ACTIVE => 'sending',
        self::STATUS_ERROR => 'error',
        self::STATUS_DONE => 'sent',
    ];

    public const EMAIL_TYPE = 'email';
    public const TELEGRAM_TYPE = 'telegram';

    public const TYPE_MAP = [
        self::EMAIL_TYPE,
        self::TELEGRAM_TYPE,
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

    public function getType(): string
    {
        return $this->type;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getTextStatus(): string
    {
        return self::TEXT_STATUS_MAP[$this->status];
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
        Assert::inArray($status, self::STATUS_MAP);
    }

    private function checkType(string $type): void
    {
        Assert::inArray($type, self::TYPE_MAP);
    }
}