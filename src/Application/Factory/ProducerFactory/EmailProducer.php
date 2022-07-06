<?php


namespace App\Application\Factory\ProducerFactory;


use App\Application\Http\Api\SingleNotify\Dto\MessageDto;
use Throwable;

class EmailProducer implements ProducerInterface
{
    public function addToQueue(string $notifyId): void
    {

    }
}