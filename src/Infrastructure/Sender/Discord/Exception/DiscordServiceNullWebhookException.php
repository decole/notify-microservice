<?php


namespace App\Infrastructure\Sender\Discord\Exception;


use Exception;

class DiscordServiceNullWebhookException extends Exception
{
    public static function nullWebhook(): self
    {
        return new self("Please configure discord webhook");
    }
}
