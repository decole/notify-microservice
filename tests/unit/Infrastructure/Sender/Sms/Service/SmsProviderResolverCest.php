<?php


namespace App\Tests\unit\Infrastructure\Sender\Sms\Service;


use App\Infrastructure\Sender\Sms\Provider\SmspilotProvider;
use App\Infrastructure\Sender\Sms\Service\SmsProviderResolver;
use App\Tests\UnitTester;
use ReflectionMethod;
use Throwable;

class SmsProviderResolverCest
{
    public function positiveCreateProvider(UnitTester $I): void
    {
        $existProvider = 'smspilot';
        $resolver = new SmsProviderResolver($existProvider);

        $method = new ReflectionMethod(SmsProviderResolver::class, 'getProvider');
        $method->setAccessible(true);

        try {
            $provider = $method->invoke($resolver);
        } catch (Throwable $exception) {
        }

        $I->assertEquals(false, isset($exception));
        $I->assertInstanceOf(SmspilotProvider::class, $provider);
    }

    public function negativeCreateProvider(UnitTester $I): void
    {
        $existProvider = 'smspilot1';
        $resolver = new SmsProviderResolver($existProvider);

        $method = new ReflectionMethod(SmsProviderResolver::class, 'getProvider');
        $method->setAccessible(true);

        try {
            $method->invoke($resolver);
        } catch (Throwable $exception) {}

        $I->assertEquals('Class "App\Infrastructure\Sender\Sms\Provider\Smspilot1Provider" not found', $exception->getMessage());
    }
}