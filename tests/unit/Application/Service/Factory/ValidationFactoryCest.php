<?php


namespace App\Tests\unit\Application\Service\Factory;


use App\Application\Exception\NotFoundEntityException;
use App\Application\Http\Api\SingleNotify\Input\MessageInput;
use App\Application\Service\Factory\ValidationFactory;
use App\Application\Service\ValidationCriteria\EmailValidationCriteria;
use App\Application\Service\ValidationCriteria\SlackValidationCriteria;
use App\Application\Service\ValidationCriteria\SmsValidationCriteria;
use App\Application\Service\ValidationCriteria\TelegramValidationCriteria;
use App\Application\Service\ValidationCriteria\VkontakteValidationCriteria;
use App\Domain\Doctrine\NotifyMessage\Enum\NotifyTypeEnum;
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
        $dto = $this->getDto(NotifyTypeEnum::EMAIL->value);
        $criteria = $this->factory->getCriteria($dto);

        $I->assertInstanceOf(EmailValidationCriteria::class, $criteria);
    }

    public function positiveTelegram(UnitTester $I): void
    {
        $dto = $this->getDto(NotifyTypeEnum::TELEGRAM->value);
        $criteria = $this->factory->getCriteria($dto);

        $I->assertInstanceOf(TelegramValidationCriteria::class, $criteria);
    }

    public function positiveVkontakte(UnitTester $I): void
    {
        $dto = $this->getDto(NotifyTypeEnum::VKONTAKTE->value);
        $criteria = $this->factory->getCriteria($dto);

        $I->assertInstanceOf(VkontakteValidationCriteria::class, $criteria);
    }

    public function positiveSlack(UnitTester $I): void
    {
        $dto = $this->getDto(NotifyTypeEnum::SLACK->value);
        $criteria = $this->factory->getCriteria($dto);

        $I->assertInstanceOf(SlackValidationCriteria::class, $criteria);
    }

    public function positiveSms(UnitTester $I): void
    {
        $dto = $this->getDto(NotifyTypeEnum::SMS->value);
        $criteria = $this->factory->getCriteria($dto);

        $I->assertInstanceOf(SmsValidationCriteria::class, $criteria);
    }

    public function negativeFactoryCriteria(UnitTester $I): void
    {
        $I->expectThrowable(new NotFoundEntityException('Validation criteria by notify type not found.'), function () {
            $dto = $this->getWrongDto();
            $this->factory->getCriteria($dto);
        });
    }

    private function getDto(string $type): MessageInput
    {
        $dto = new MessageInput();

        if ($type === NotifyTypeEnum::EMAIL->value) {
            $dto->type = NotifyTypeEnum::EMAIL->value;
            $dto->email = 'test@test.ru';
        }

        if ($type === NotifyTypeEnum::TELEGRAM->value) {
            $dto->type = NotifyTypeEnum::TELEGRAM->value;
            $dto->userId = 1234567890;
        }

        if ($type === NotifyTypeEnum::VKONTAKTE->value) {
            $dto->type = NotifyTypeEnum::VKONTAKTE->value;
        }

        if ($type === NotifyTypeEnum::SLACK->value) {
            $dto->type = NotifyTypeEnum::SLACK->value;
        }

        if ($type === NotifyTypeEnum::SMS->value) {
            $dto->type = NotifyTypeEnum::SMS->value;
            $dto->phone = 799988877766;
        }

        $dto->message = 'test';

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