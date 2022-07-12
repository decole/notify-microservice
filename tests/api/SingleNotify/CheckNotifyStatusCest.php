<?php


namespace App\Tests\api\SingleNotify;


use App\Application\Http\Api\SingleNotify\Dto\MessageDto;
use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Infrastructure\Doctrine\Service\NotifyMessageService;
use App\Tests\ApiTester;

class CheckNotifyStatusCest
{
    public function checkStatusNotify(ApiTester $I): void
    {
        $entity = $this->createEntity($I);

        $id = $entity->getId()->toString();

        $I->sendGet("/v1/check-status/{$id}");
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'id' => $id,
            'status' => NotifyMessage::TEXT_STATUS_MAP[NotifyMessage::STATUS_IN_QUEUE],
            'lastModifiedStatusByUTC' => $entity->getCreatedAt()->format('Y-m-d H:i:s'),
        ]);
    }

    private function createEntity(ApiTester $I): NotifyMessage
    {
        $body = [
            'type' => NotifyMessage::EMAIL_TYPE,
            'email' => 'test@test.ru',
            'message' => 'test',
        ];

        $dto = new MessageDto(NotifyMessage::EMAIL_TYPE, $body);

        /** @var NotifyMessageService $service */
        $service = $I->grabService(NotifyMessageService::class);

        return $service->create($dto);
    }
}