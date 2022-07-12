<?php


namespace App\Tests\api\SingleNotify;


use App\Application\Http\Api\SingleNotify\Dto\MessageDto;
use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Infrastructure\Doctrine\Service\NotifyMessageService;
use App\Tests\ApiTester;
use Ramsey\Uuid\Uuid;

class CheckNotifyStatusCest
{
    public function checkStatusNotifyInQueue(ApiTester $I): void
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

    public function checkStatusActive(ApiTester $I): void
    {
        $entity = $this->createEntity($I);

        $entity->setStatus(NotifyMessage::STATUS_ACTIVE);

        $this->updateEntity($entity, $I);

        $id = $entity->getId()->toString();

        $I->sendGet("/v1/check-status/{$id}");
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'id' => $id,
            'status' => NotifyMessage::TEXT_STATUS_MAP[NotifyMessage::STATUS_ACTIVE],
            'lastModifiedStatusByUTC' => $entity->getUpdatedAt()->format('Y-m-d H:i:s'),
        ]);
    }

    public function checkStatusDone(ApiTester $I): void
    {
        $entity = $this->createEntity($I);

        $entity->setStatus(NotifyMessage::STATUS_DONE);

        $this->updateEntity($entity, $I);

        $id = $entity->getId()->toString();

        $I->sendGet("/v1/check-status/{$id}");
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'id' => $id,
            'status' => NotifyMessage::TEXT_STATUS_MAP[NotifyMessage::STATUS_DONE],
            'lastModifiedStatusByUTC' => $entity->getUpdatedAt()->format('Y-m-d H:i:s'),
        ]);
    }

    public function checkStatusError(ApiTester $I): void
    {
        $entity = $this->createEntity($I);

        $entity->setStatus(NotifyMessage::STATUS_ERROR);

        $this->updateEntity($entity, $I);

        $id = $entity->getId()->toString();

        $I->sendGet("/v1/check-status/{$id}");
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'id' => $id,
            'status' => NotifyMessage::TEXT_STATUS_MAP[NotifyMessage::STATUS_ERROR],
            'lastModifiedStatusByUTC' => $entity->getUpdatedAt()->format('Y-m-d H:i:s'),
        ]);
    }

    public function notFoundNotifyMessage(ApiTester $I): void
    {
        $id = Uuid::uuid4();

        $I->sendGet("/v1/check-status/{$id}");
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->seeResponseIsJson();
        $I->canSeeResponseCodeIs(400);
        $I->seeResponseContainsJson([
            'error' => "Notify by id {$id} not found",
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

        $service = $this->getService($I);

        return $service->create($dto);
    }

    private function updateEntity(NotifyMessage $entity, ApiTester $I): void
    {
        $service = $this->getService($I);

        $service->update($entity);
    }

    private function getService(ApiTester $I): NotifyMessageService
    {
        return $I->grabService(NotifyMessageService::class);
    }
}