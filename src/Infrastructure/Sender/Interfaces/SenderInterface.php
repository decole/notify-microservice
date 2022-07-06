<?php


namespace App\Infrastructure\Sender\Interfaces;


use App\Infrastructure\Doctrine\Interfaces\EntityInterface;

interface SenderInterface
{
    public function send(EntityInterface $message): void;
}