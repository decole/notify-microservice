<?php


namespace App\Infrastructure\Sender\Sms\Service;


use App\Infrastructure\Sender\Sms\Exception\SmsServiceException;
use App\Infrastructure\Sender\Sms\Provider\SmsProviderInterface;
use Webmozart\Assert\Assert;

class SmsProviderResolver
{
    public function __construct(
        private readonly ?string $provider = null
    ) {
        if ($this->provider === null) {
            throw SmsServiceException::providerEmpty();
        }
    }

    public function send(string $phone, string $notify): mixed
    {
        return $this->getProvider()->broadcast($phone, $notify);
    }

    private function getProvider(): SmsProviderInterface
    {
        $class = sprintf(
            'App\Infrastructure\Sender\Sms\Provider\%sProvider',
            ucfirst($this->provider),
        );

        $provider = new $class;

        Assert::isInstanceOf($provider, SmsProviderInterface::class);

        return $provider;
    }
}