<?php

namespace App\Tests\api\SingleNotify;

use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Tests\ApiTester;

class SingleSendCest
{
    public function positiveSend(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/v1/send', [
            'type' => NotifyMessage::EMAIL_TYPE,
            'email' => 'decole@rambler.ru',
            'message' => 'tester',
        ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'status' => 'in queue',
        ]);
        $I->seeInRepository(NotifyMessage::class, [
            'type' => 'email',
            'status' => NotifyMessage::STATUS_IN_QUEUE,
        ]);
    }

    public function negativeSend(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/v1/send', [
            'type' => 'error',
            'message' => 'test',
        ]);
        $I->seeResponseIsJson();
        $I->canSeeResponseCodeIs(400);
        $I->seeResponseContainsJson([
            'error' => 'Validation criteria by notify type not found.',
        ]);
    }
}