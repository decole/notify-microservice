<?php


namespace App\Tests\api\SingleNotify;


use App\Application\Http\Api\SingleNotify\Dto\MessageDto;
use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Domain\Doctrine\NotifyMessage\Enum\NotifyStatusEnum;
use App\Domain\Doctrine\NotifyMessage\Enum\NotifyTypeEnum;
use App\Infrastructure\Doctrine\Service\NotifyMessageService;
use App\Tests\ApiTester;
use Ramsey\Uuid\Uuid;

class CheckNotifyStatusCest
{
    public function checkStatusNotifyInQueue(ApiTester $I): void
    {
        $entity = $this->createEntity($I);
        $id = $entity->getId()->toString();
        $status = NotifyStatusEnum::tryFrom(NotifyStatusEnum::IN_QUEUE->value);

        $I->sendGet("/v1/check-status/{$id}");
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'id' => $id,
            'status' => $status->getTextStatus(),
            'lastModifiedStatusByUTC' => $entity->getCreatedAt()->format('Y-m-d H:i:s'),
        ]);
    }

    public function checkStatusActive(ApiTester $I): void
    {
        $entity = $this->createEntity($I);
        $entity->setStatus(NotifyStatusEnum::ACTIVE->value);
        $this->updateEntity($entity, $I);
        $id = $entity->getId()->toString();
        $status = NotifyStatusEnum::tryFrom(NotifyStatusEnum::ACTIVE->value);

        $I->sendGet("/v1/check-status/{$id}");
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'id' => $id,
            'status' => $status->getTextStatus(),
            'lastModifiedStatusByUTC' => $entity->getUpdatedAt()->format('Y-m-d H:i:s'),
        ]);
    }

    public function checkStatusDone(ApiTester $I): void
    {
        $entity = $this->createEntity($I);
        $entity->setStatus(NotifyStatusEnum::DONE->value);
        $this->updateEntity($entity, $I);
        $id = $entity->getId()->toString();
        $status = NotifyStatusEnum::tryFrom(NotifyStatusEnum::DONE->value);

        $I->sendGet("/v1/check-status/{$id}");
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'id' => $id,
            'status' => $status->getTextStatus(),
            'lastModifiedStatusByUTC' => $entity->getUpdatedAt()->format('Y-m-d H:i:s'),
        ]);
    }

    public function checkStatusError(ApiTester $I): void
    {
        $entity = $this->createEntity($I);
        $entity->setStatus(NotifyStatusEnum::ERROR->value);
        $this->updateEntity($entity, $I);
        $id = $entity->getId()->toString();
        $status = NotifyStatusEnum::tryFrom(NotifyStatusEnum::ERROR->value);

        $I->sendGet("/v1/check-status/{$id}");
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'id' => $id,
            'status' => $status->getTextStatus(),
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
            'error' => 'An error occurred while executing the request',
            'result' => false,
            'errorText' => "Notify by id {$id} not found",
        ]);
    }

    private function createEntity(ApiTester $I): NotifyMessage
    {
        $body = [
            'type' => NotifyTypeEnum::EMAIL->value,
            'email' => 'test@test.ru',
            'message' => 'test',
        ];

        $dto = new MessageDto(NotifyTypeEnum::EMAIL->value, $body);

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