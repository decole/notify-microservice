<?php


namespace App\Tests\unit\Application\Service\ValidationCriteria;


use App\Application\Http\Api\SingleNotify\Input\MessageInput;
use App\Application\Service\ValidationCriteria\EmailValidationCriteria;
use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Tests\UnitTester;
use Symfony\Component\Validator\ConstraintViolationList;

class EmailValidationCriteriaCest
{
    public function positiveEmailValidation(UnitTester $I): void
    {
        $criteria = new EmailValidationCriteria($this->getDto());
        $list = new ConstraintViolationList();
        $criteria->validate($list);

        $I->assertEquals(0, $list->count());
    }

    public function negativeEmailValidation(UnitTester $I): void
    {
        $criteria = new EmailValidationCriteria($this->getWrongDto());
        $list = new ConstraintViolationList();
        $criteria->validate($list);

        $I->assertEquals(1, $list->count());
    }

    private function getDto(): MessageInput
    {
        $dto = new MessageInput();
        $dto->type = NotifyMessage::EMAIL_TYPE;
        $dto->message = 'test';
        $dto->email = 'test@test.ru';

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