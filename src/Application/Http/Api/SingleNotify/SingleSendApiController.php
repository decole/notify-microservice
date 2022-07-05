<?php


namespace App\Application\Http\Api\SingleNotify;


use App\Application\Helper\StringHelper;
use App\Application\Http\Api\SingleNotify\Dto\MessageDto;
use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Infrastructure\Doctrine\Service\NotifyMessageService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class SingleSendApiController
{
    public function __construct(private readonly NotifyMessageService $service)
    {
    }

    #[Route('/send')]
    public function number(): JsonResponse
    {
        $number = random_int(0, 100);

        $result = StringHelper::sanitize($number);

        // validation

        // create DTO
        $type = 'email';
        $message = ['example' => 'lol'];
        $status = NotifyMessage::STATUS_IN_QUEUE;
        $dto = new MessageDto($type, $status, $message);

        // save from DB
        $message = $this->service->create($dto);

        return new JsonResponse([
            'id' => $message->getId(),
        ]);
    }
}