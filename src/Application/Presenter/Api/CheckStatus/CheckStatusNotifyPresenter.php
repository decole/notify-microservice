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
        if ($this->message->getUpdatedAt() !== null) {
            return $this->message->getUpdatedAt()->format('Y-m-d H:i:s');
        }

        return $this->message->getCreatedAt()->format('Y-m-d H:i:s');
    }
}