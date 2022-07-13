<?php


namespace App\Tests\unit\Application\Service\Factory;


use App\Application\Exception\NotFoundEntityException;
use App\Application\Http\Api\SingleNotify\Input\MessageInput;
use App\Application\Service\Factory\ValidationFactory;
use App\Application\Service\ValidationCriteria\EmailValidationCriteria;
use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Tests\UnitTester;

class ValidationFactoryCest
{
    private ValidationFactory $factory;

    public function setUp(UnitTester $I): void
    {
        $this->factory = $I->grabService(ValidationFactory::class);
    }

    public function positiveEmail(UnitTester $I): void
    {
        $dto = $this->getDto();
        $criteria = $this->factory->getCriteria($dto);

        $I->assertInstanceOf(EmailValidationCriteria::class, $criteria);
    }

    public function negativeEmail(UnitTester $I): void
    {
        $I->expectThrowable(new NotFoundEntityException('Validation criteria by notify type not found.'), function () {
            $dto = $this->getWrongDto();
            $this->factory->getCriteria($dto);
        });
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
        $dto->message = 'error';

        return $dto;
    }
}