<?php

namespace App\Tests\api\SingleNotify;

use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Domain\Doctrine\NotifyMessage\Enum\NotifyStatusEnum;
use App\Domain\Doctrine\NotifyMessage\Enum\NotifyTypeEnum;
use App\Tests\ApiTester;

class SingleSendSlackCest
{
    public function positiveSend(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/v1/send', [
            'type' => NotifyTypeEnum::SLACK->value,
            'message' => 'tester',
        ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'status' => 'in queue',
        ]);
        $I->seeInRepository(NotifyMessage::class, [
            'type' => NotifyTypeEnum::SLACK->value,
            'status' => NotifyStatusEnum::IN_QUEUE->value,
        ]);
    }

    public function negativeWrongType(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/v1/send', [
            'type' => 'error',
            'message' => 'test',
        ]);
        $I->seeResponseIsJson();
        $I->canSeeResponseCodeIs(400);
        $I->seeResponseContainsJson([
            'error' => 'An error occurred while executing the request',
            'result' => false,
            'errorText' => 'Validation criteria by notify type not found.',
        ]);
    }

    public function negativeEmptyMessage(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/v1/send', [
            'type' => NotifyTypeEnum::SLACK->value,
            'message' => '',
        ]);
        $I->seeResponseIsJson();
        $I->canSeeResponseCodeIs(400);
        $I->seeResponseContainsJson([
            'error' => [
                'message' => 'This value should not be null.',
            ],
        ]);
    }

    public function negativeNullMessage(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/v1/send', [
            'type' => NotifyTypeEnum::SLACK->value,
            'message' => null,
        ]);
        $I->seeResponseIsJson();
        $I->canSeeResponseCodeIs(400);
        $I->seeResponseContainsJson([
            'error' => [
                'message' => 'This value should not be null.',
            ],
        ]);
    }
}