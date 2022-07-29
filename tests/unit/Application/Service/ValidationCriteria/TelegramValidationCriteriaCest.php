<?php


namespace App\Tests\unit\Application\Service\ValidationCriteria;


use App\Application\Http\Api\SingleNotify\Input\MessageInput;
use App\Application\Service\ValidationCriteria\TelegramValidationCriteria;
use App\Domain\Doctrine\NotifyMessage\Enum\NotifyTypeEnum;
use App\Tests\UnitTester;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Validator\ConstraintViolationList;

class TelegramValidationCriteriaCest
{
    private Generator $faker;

    public function setUp(): void
    {
        $this->faker = Factory::create();
    }

    public function positiveTelegramValidation(UnitTester $I): void
    {
        $criteria = new TelegramValidationCriteria($this->getDto());
        $list = new ConstraintViolationList();
        $criteria->validate($list);

        $I->assertEquals(0, $list->count());
    }

    public function negativeTelegramValidation(UnitTester $I): void
    {
        $criteria = new TelegramValidationCriteria($this->getWrongDto());
        $list = new ConstraintViolationList();
        $criteria->validate($list);

        $I->assertEquals(3, $list->count());
    }

    private function getDto(): MessageInput
    {
        $dto = new MessageInput();
        $dto->type = NotifyTypeEnum::TELEGRAM->value;
        $dto->userId = $this->faker->biasedNumberBetween(10000000, 99999999999);
        $dto->message = 'test';

        return $dto;
    }

    private function getWrongDto(): MessageInput
    {
        $dto = new MessageInput();
        $dto->type = 'error';
        $dto->message = 'test';

        return $dto;
    }
}