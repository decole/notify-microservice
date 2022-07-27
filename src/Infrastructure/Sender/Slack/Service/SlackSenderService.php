<?php


namespace App\Infrastructure\Sender\Slack\Service;


use Maknz\Slack\Client;

class SlackSenderService
{
    public function __construct(
        private string $webhook,
    ) {
    }

    public function send(string $message): void
    {
        $client = new Client($this->webhook);
        $client->send($message);
    }
}