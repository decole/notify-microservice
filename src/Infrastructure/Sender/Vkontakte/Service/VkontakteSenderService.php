<?php


namespace App\Infrastructure\Sender\Vkontakte\Service;


use App\Infrastructure\Sender\Vkontakte\Exception\VkontakteServiceException;
use VK\Client\VKApiClient;

class VkontakteSenderService
{
    private const API_VERSION = '5.101';

    private VKApiClient $api;

    public function __construct(
        private readonly ?string $accessToken = null,
        private readonly ?string $groupId = null,
        private readonly ?string $peerId = null,
    ) {
        if ($accessToken === null || $groupId === null || $peerId === null) {
            throw VkontakteServiceException::apiTokenEmpty();
        }

        $this->api = new VKApiClient(self::API_VERSION);
    }

    public function send(string $message): mixed
    {
        return $this->api->messages()->send($this->accessToken, [
            'group_id' => $this->groupId,
            'peer_id' => $this->peerId,
            'message' => $message,
            'random_id' => random_int(1000, 999999999),
        ]);
    }
}