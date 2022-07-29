<?php


namespace App\Tests\api\SingleNotify;


use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Domain\Doctrine\NotifyMessage\Enum\NotifyStatusEnum;
use App\Domain\Doctrine\NotifyMessage\Enum\NotifyTypeEnum;
use App\Tests\ApiTester;
use Faker\Factory;
use Faker\Generator;

class SingleSendVkontakteCest
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
            'type' => NotifyTypeEnum::VKONTAKTE->value,
            'message' => $this->faker->text,
        ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'status' => 'in queue',
        ]);
        $I->seeInRepository(NotifyMessage::class, [
            'type' => NotifyTypeEnum::VKONTAKTE->value,
            'status' => NotifyStatusEnum::IN_QUEUE->value,
        ]);
    }

    public function negativeEmptyMessage(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/v1/send', [
            'type' => NotifyTypeEnum::VKONTAKTE->value,
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
            'type' => NotifyTypeEnum::VKONTAKTE->value,
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
