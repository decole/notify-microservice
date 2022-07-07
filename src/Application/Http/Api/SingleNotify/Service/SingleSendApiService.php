<?php


namespace App\Application\Http\Api\SingleNotify\Service;


use App\Application\Helper\StringHelper;
use App\Application\Http\Api\SingleNotify\Dto\MessageDto;
use App\Application\Http\Api\SingleNotify\Input\MessageInput;
use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use JsonException;
use Symfony\Component\HttpFoundation\Request;

final class SingleSendApiService
{
    /**
     * @throws JsonException
     */
    public function createInputDto(Request $request): MessageInput
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $input = new MessageInput();

        if ($data === null) {
            return $input;
        }

        foreach ($data as $param => $value) {
            if (property_exists($input, $param)) {
                $input->$param = StringHelper::sanitize($value);
            }
        }

        return $input;
    }

    public function createMessageDto(MessageInput $input): MessageDto
    {
        return new MessageDto($input->type, $input->toArray());
    }

    /**
     * @throws JsonException
     */
    public function getPublishQueueMessage(NotifyMessage $message): string
    {
        return json_encode(
            [
                'messageId' => $message->getId()->toString(),
            ],
            JSON_THROW_ON_ERROR
        );
    }
}