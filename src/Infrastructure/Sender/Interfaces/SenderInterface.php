<?php


namespace App\Infrastructure\Sender\Interfaces;


use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;

interface SenderInterface
{
    public function send(NotifyMessage $message): void;
}