<?php


namespace App\Application\Presenter\Api\SingleNotify;


use App\Application\Presenter\Api\AbstractPresenter;
use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;

final class SingleSendNotifyApiPresenter extends AbstractPresenter
{
    protected const SERVER_HTTP_CODE = 201;

    private const SUCCESS_STATUS = 'in queue';

    public function __construct(private readonly NotifyMessage $message)
    {
    }

    public function getResult(): array
    {
        return [
            'status' => self::SUCCESS_STATUS,
            'notifyId' => $this->message->getId(),
        ];
    }
}