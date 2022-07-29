<?php


namespace App\Tests\api\SingleNotify;


use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Domain\Doctrine\NotifyMessage\Enum\NotifyStatusEnum;
use App\Domain\Doctrine\NotifyMessage\Enum\NotifyTypeEnum;
use App\Tests\ApiTester;
use Faker\Factory;
use Faker\Generator;

class SingleSendSmsCest
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
            'type' => NotifyTypeEnum::SMS->value,
            'phone' => $this->faker->phoneNumber,
            'message' => $this->faker->text,
        ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'status' => 'in queue',
        ]);
        $I->seeInRepository(NotifyMessage::class, [
            'type' => NotifyTypeEnum::SMS->value,
            'status' => NotifyStatusEnum::IN_QUEUE->value,
        ]);
    }

    public function negativeWrongType(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/v1/send', [
            'type' => 'error',
            'message' => 'test',
            'phone' => $this->faker->phoneNumber,
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
            'type' => NotifyTypeEnum::SMS->value,
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
            'type' => NotifyTypeEnum::SMS->value,
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

    public function negativeNullPhone(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/v1/send', [
            'type' => NotifyTypeEnum::SMS->value,
            'message' => $this->faker->text,
            'phone' => null,
        ]);
        $I->seeResponseIsJson();
        $I->canSeeResponseCodeIs(400);
        $I->seeResponseContainsJson([
            'error' => [
                'phone' => 'field is required!',
            ],
        ]);
    }
}