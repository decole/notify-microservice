<?php


namespace App\Application\Factory\ProducerFactory;


use App\Application\Http\Api\SingleNotify\Dto\MessageDto;

interface ProducerInterface
{
    public function addToQueue(string $notifyId): void;
}