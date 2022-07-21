<?php


namespace App\Tests\api\SingleNotify;


use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Tests\ApiTester;
use Faker\Factory;
use Faker\Generator;

class SingleSendTelegramCest
{
    private Generator $faker;

    public function setUp(): void
    {
        $this->faker = Factory::create();
    }

    public function positiveSend(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/v1/send', [
            'type' => NotifyMessage::TELEGRAM_TYPE,
            'userId' => $this->faker->numberBetween(100000000, 99999999999),
            'message' => 'tester',
        ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'status' => 'in queue',
        ]);
        $I->seeInRepository(NotifyMessage::class, [
            'type' => NotifyMessage::TELEGRAM_TYPE,
            'status' => NotifyMessage::STATUS_IN_QUEUE,
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
            'type' => NotifyMessage::TELEGRAM_TYPE,
            'userId' => $this->faker->numberBetween(100000000, 99999999999),
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
            'type' => NotifyMessage::TELEGRAM_TYPE,
            'userId' => $this->faker->numberBetween(100000000, 99999999999),
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
            'type' => NotifyMessage::TELEGRAM_TYPE,
            'userId' => null,
            'message' => 'test',
        ]);
        $I->seeResponseIsJson();
        $I->canSeeResponseCodeIs(400);
        $I->seeResponseContainsJson([
            'error' => [
                'userId' => 'userId can be integer',
            ],
        ]);
    }

    public function negativeEmptyTelegramId(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/v1/send', [
            'type' => NotifyMessage::TELEGRAM_TYPE,
            'userId' => '',
            'message' => 'test',
        ]);
        $I->seeResponseIsJson();
        $I->canSeeResponseCodeIs(400);
        $I->seeResponseContainsJson([
            'error' => [
                'userId' => 'userId can be integer',
            ],
        ]);
    }

    public function negativeWrongEmail(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/v1/send', [
            'type' => NotifyMessage::TELEGRAM_TYPE,
            'userId' => $this->faker->word,
            'message' => 'test',
        ]);
        $I->seeResponseIsJson();
        $I->canSeeResponseCodeIs(400);
        $I->seeResponseContainsJson([
            'error' => [
                'userId' => 'userId can be integer',
            ],
        ]);
    }
}