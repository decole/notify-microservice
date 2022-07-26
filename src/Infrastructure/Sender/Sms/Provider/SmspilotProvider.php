<?php


namespace App\Infrastructure\Sender\Sms\Provider;


use App\Infrastructure\Sender\Sms\Exception\SmsServiceException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * watch https://smspilot.ru/apikey.php#sms
 */
class SmspilotProvider implements SmsProviderInterface
{
    private const URI = 'https://smspilot.ru/api.php';

    /**
     * @throws GuzzleException|SmsServiceException
     */
    public function broadcast(string $phone, string $notify)
    {
        $apiKey = $_ENV['SMS_TOKEN'];

        if ($apiKey === null || $apiKey === '') {
            throw SmsServiceException::apiTokenEmpty();
        }

        $client = new Client();
        $response = $client->get(self::URI, [
            'send' => $notify,
            'to' => $phone,
            'apikey' => $apiKey,
            'format' => 'v'
        ]);

        return  $response->getBody();
    }
}