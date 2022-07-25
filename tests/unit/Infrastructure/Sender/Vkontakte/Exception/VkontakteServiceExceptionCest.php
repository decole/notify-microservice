<?php


namespace App\Tests\unit\Infrastructure\Sender\Vkontakte\Exception;


use App\Infrastructure\Sender\Vkontakte\Exception\VkontakteServiceException;
use App\Tests\UnitTester;

class VkontakteServiceExceptionCest
{
    public function positiveException(UnitTester $I): void
    {
        $I->expectThrowable(VkontakteServiceException::apiTokenEmpty(), function () {
            throw VkontakteServiceException::apiTokenEmpty();
        });
    }
}