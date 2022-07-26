<?php


namespace App\Tests\unit\Application\Service\ValidationCriteria;


use App\Application\Http\Api\SingleNotify\Input\MessageInput;
use App\Application\Service\ValidationCriteria\SmsValidationCriteria;
use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Tests\UnitTester;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Validator\ConstraintViolationList;

class SmsValidationCriteriaCest
{
    private Generator $faker;

    public function setUp(): void
    {
        $this->faker = Factory::create();
    }

    public function positiveValidation(UnitTester $I): void
    {
        $criteria = new SmsValidationCriteria($this->getDto());
        $list = new ConstraintViolationList();
        $criteria->validate($list);

        $I->assertEquals(0, $list->count());
    }

    public function negativeValidation(UnitTester $I): void
    {
        $criteria = new SmsValidationCriteria($this->getWrongDto());
        $list = new ConstraintViolationList();
        $criteria->validate($list);

        $I->assertEquals(1, $list->count());
    }

    private function getDto(): MessageInput
    {
        $dto = new MessageInput();
        $dto->type = NotifyMessage::SMS_TYPE;
        $dto->message = 'test';
        $dto->phone = $this->faker->phoneNumber;

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