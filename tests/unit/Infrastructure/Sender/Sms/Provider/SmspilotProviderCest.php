<?php


namespace App\Tests\unit\Infrastructure\Sender\Sms\Provider;


use App\Infrastructure\Sender\Sms\Exception\SmsServiceException;
use App\Infrastructure\Sender\Sms\Provider\SmspilotProvider;
use App\Infrastructure\Sender\Sms\Provider\SmsProviderInterface;
use App\Tests\UnitTester;
use TypeError;

class SmspilotProviderCest
{
    public function negativeSend(UnitTester $I): void
    {
        $_ENV['SMS_TOKEN'] = '';
        /** @var SmspilotProvider $service */
        $service = $I->grabService(SmspilotProvider::class);
        $I->assertInstanceOf(SmsProviderInterface::class, $service);
        $I->expectThrowable(SmsServiceException::apiTokenEmpty(), function () use ($service){
            $service->broadcast(31312132, 'qweqeqewqewqew');
        });
    }

    public function negativeSendWithEmptyPhone(UnitTester $I): void
    {
        $_ENV['SMS_TOKEN'] = '';
        /** @var SmspilotProvider $service */
        $service = $I->grabService(SmspilotProvider::class);
        $I->assertInstanceOf(SmsProviderInterface::class, $service);
        $I->expectThrowable(
            new TypeError('App\Infrastructure\Sender\Sms\Provider\SmspilotProvider::broadcast(): Argument #1 ($phone) must be of type string, null given, called in /var/www/tests/unit/Infrastructure/Sender/Sms/Provider/SmspilotProviderCest.php on line 35'),
            function () use ($service){
                $service->broadcast(null, 'qweqeqewqewqew');
            }
        );
    }
}