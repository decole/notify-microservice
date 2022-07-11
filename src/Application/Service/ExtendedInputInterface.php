<?php


namespace App\Application\Service;


interface ExtendedInputInterface extends InputInterface
{
    public function toArray(): array;
}