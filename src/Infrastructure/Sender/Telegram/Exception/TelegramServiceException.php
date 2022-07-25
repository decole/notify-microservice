<?php


namespace App\Infrastructure\Sender\Telegram\Exception;


use Exception;

class TelegramServiceException extends Exception
{
    public static function apiTokenEmpty(): self
    {
        return new self("Please configure telegram bot Token");
    }
}