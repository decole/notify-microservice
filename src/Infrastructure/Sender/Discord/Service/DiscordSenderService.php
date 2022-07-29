<?php


namespace App\Infrastructure\Sender\Discord\Service;


use App\Infrastructure\Sender\Discord\Exception\DiscordServiceNullWebhookException;
use \GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class DiscordSenderService
{
    private string $webhookUri;
    private \GuzzleHttp\Client $client;

    public function __construct(?string $webhookUri = null)
    {
        if (!$webhookUri) {
            throw DiscordServiceNullWebhookException::nullWebhook();
        }

        $this->webhookUri = $webhookUri;

        $this->client = new Client([
            'headers' => ['Accept' => 'application/json', 'Content-Type' => 'application/json'],
            'allow_redirects' => false,
        ]);
    }


    public function sendMessage(string $message): ResponseInterface
    {
        return $this->client->post($this->webhookUri, [
            'json' => [
                'content' => $message,
            ],
        ]);
    }
}
