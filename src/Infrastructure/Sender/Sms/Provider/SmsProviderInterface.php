<?php


namespace App\Infrastructure\Sender\Sms\Provider;


interface SmsProviderInterface
{
    public function broadcast(string $phone, string $notify);
}