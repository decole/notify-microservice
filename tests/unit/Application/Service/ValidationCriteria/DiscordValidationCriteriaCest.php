<?php


namespace App\Tests\unit\Application\Service\ValidationCriteria;


use App\Application\Http\Api\SingleNotify\Input\MessageInput;
use App\Application\Service\ValidationCriteria\DiscordValidationCriteria;
use App\Domain\Doctrine\NotifyMessage\Enum\NotifyTypeEnum;
use App\Tests\UnitTester;
use Symfony\Component\Validator\ConstraintViolationList;

class DiscordValidationCriteriaCest
{
    public function positiveValidation(UnitTester $I): void
    {
        $criteria = new DiscordValidationCriteria($this->getDto());
        $list = new ConstraintViolationList();
        $criteria->validate($list);

        $I->assertEquals(0, $list->count());
    }

    public function negativeValidation(UnitTester $I): void
    {
        $criteria = new DiscordValidationCriteria($this->getWrongDto());
        $list = new ConstraintViolationList();
        $criteria->validate($list);

        $I->assertEquals(1, $list->count());
    }

    private function getDto(): MessageInput
    {
        $dto = new MessageInput();
        $dto->type = NotifyTypeEnum::DISCORD->value;
        $dto->message = 'test';

        return $dto;
    }

    private function getWrongDto(): MessageInput
    {
        $dto = new MessageInput();
        $dto->message = 'test';

        return $dto;
    }
}
