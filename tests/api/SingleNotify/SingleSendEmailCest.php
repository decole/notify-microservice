<?php


namespace App\Tests\api\SingleNotify;


use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Tests\ApiTester;

class SingleSendEmailCest
{
    public function positiveSend(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/v1/send', [
            'type' => NotifyMessage::EMAIL_TYPE,
            'email' => 'test@test.ru',
            'message' => 'tester',
        ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'status' => 'in queue',
        ]);
        $I->seeInRepository(NotifyMessage::class, [
            'type' => NotifyMessage::EMAIL_TYPE,
            'status' => NotifyMessage::STATUS_IN_QUEUE,
        ]);
    }

    public function negativeEmptyMessage(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/v1/send', [
            'type' => NotifyMessage::EMAIL_TYPE,
            'email' => 'decole@rambler.ru',
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
            'type' => NotifyMessage::EMAIL_TYPE,
            'email' => 'decole@rambler.ru',
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

    public function negativeNullEmail(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/v1/send', [
            'type' => NotifyMessage::EMAIL_TYPE,
            'email' => null,
            'message' => 'test',
        ]);
        $I->seeResponseIsJson();
        $I->canSeeResponseCodeIs(400);
        $I->seeResponseContainsJson([
            'error' => [
                'email' => 'field is required!',
            ],
        ]);
    }

    public function negativeEmptyEmail(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/v1/send', [
            'type' => NotifyMessage::EMAIL_TYPE,
            'email' => '',
            'message' => 'test',
        ]);
        $I->seeResponseIsJson();
        $I->canSeeResponseCodeIs(400);
        $I->seeResponseContainsJson([
            'error' => [
                'email' => 'field is required!',
            ],
        ]);
    }

    public function negativeWrongEmail(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/v1/send', [
            'type' => NotifyMessage::EMAIL_TYPE,
            'email' => 'test@mimimi',
            'message' => 'test',
        ]);
        $I->seeResponseIsJson();
        $I->canSeeResponseCodeIs(400);
        $I->seeResponseContainsJson([
            'error' => [
                'email' => 'This value is not a valid email address.',
            ],
        ]);
    }
}
