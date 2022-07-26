<?php


namespace App\Infrastructure\Sender\Vkontakte\Exception;


use Exception;

class VkontakteServiceException extends Exception
{
    public static function apiTokenEmpty(): self
    {
        return new self('Please configure vkontakte access key');
    }
}