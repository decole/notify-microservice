<?php


namespace App\Tests\unit\Infrastructure\Sender\Sms\Exception;


use App\Infrastructure\Sender\Sms\Exception\SmsServiceException;
use App\Tests\UnitTester;

class SmsServiceExceptionCest
{
    public function positiveException(UnitTester $I): void
    {
        $I->expectThrowable(SmsServiceException::apiTokenEmpty(), function () {
            throw SmsServiceException::apiTokenEmpty();
        });

        $I->expectThrowable(SmsServiceException::providerEmpty(), function () {
            throw SmsServiceException::providerEmpty();
        });
    }
}