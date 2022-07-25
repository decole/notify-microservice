<?php


namespace App\Tests\unit\Infrastructure\Sender\Telegram\Exception;


use App\Infrastructure\Sender\Telegram\Exception\TelegramServiceException;
use App\Tests\UnitTester;

class TelegramServiceExceptionCest
{
    public function positiveException(UnitTester $I): void
    {
        $I->expectThrowable(TelegramServiceException::apiTokenEmpty(), function () {
            throw TelegramServiceException::apiTokenEmpty();
        });
    }
}