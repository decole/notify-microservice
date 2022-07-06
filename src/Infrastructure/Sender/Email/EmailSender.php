<?php


namespace App\Infrastructure\Sender\Email;


use App\Infrastructure\Doctrine\Interfaces\EntityInterface;
use App\Infrastructure\Sender\Interfaces\SenderInterface;

final class EmailSender implements SenderInterface
{
    public function send(EntityInterface $message): void
    {
        // TODO: Implement send() method.
    }
}