<?php


namespace App\Application\Presenter\Api\CheckStatus;


use App\Application\Presenter\Api\AbstractPresenter;
use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;

final class CheckStatusNotifyPresenter extends AbstractPresenter
{
    public function __construct(private readonly NotifyMessage $message)
    {
    }

    public function getResult(): array
    {
        return [
            'id' => $this->message->getId()->toString(),
            'status' => $this->message->getTextStatus(),
            'lastModifiedStatusByUTC' => $this->getDate(),
        ];
    }

    private function getDate(): string
    {
        return ($this->message->getUpdatedAt() ?? $this->message->getCreatedAt())->format('Y-m-d H:i:s');
    }
}