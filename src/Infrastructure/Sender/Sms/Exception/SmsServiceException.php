<?php


namespace App\Infrastructure\Sender\Sms\Exception;


use Exception;

class SmsServiceException extends Exception
{
    public static function apiTokenEmpty(): self
    {
        return new self('Please configure sms token');
    }

    public static function providerEmpty(): self
    {
        return new self('Please configure sms provider in .env file');
    }
}