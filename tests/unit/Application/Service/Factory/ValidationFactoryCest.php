<?php


namespace App\Tests\unit\Application\Service\Factory;


use App\Application\Exception\NotFoundEntityException;
use App\Application\Http\Api\SingleNotify\Input\MessageInput;
use App\Application\Service\Factory\ValidationFactory;
use App\Application\Service\ValidationCriteria\EmailValidationCriteria;
use App\Application\Service\ValidationCriteria\TelegramValidationCriteria;
use App\Application\Service\ValidationCriteria\VkontakteValidationCriteria;
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
        $dto = $this->getDto(NotifyMessage::EMAIL_TYPE);
        $criteria = $this->factory->getCriteria($dto);

        $I->assertInstanceOf(EmailValidationCriteria::class, $criteria);
    }

    public function positiveTelegram(UnitTester $I): void
    {
        $dto = $this->getDto(NotifyMessage::TELEGRAM_TYPE);
        $criteria = $this->factory->getCriteria($dto);

        $I->assertInstanceOf(TelegramValidationCriteria::class, $criteria);
    }

    public function positiveVkontakte(UnitTester $I): void
    {
        $dto = $this->getDto(NotifyMessage::VKONTAKTE_TYPE);
        $criteria = $this->factory->getCriteria($dto);

        $I->assertInstanceOf(VkontakteValidationCriteria::class, $criteria);
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

        if ($type === NotifyMessage::EMAIL_TYPE) {
            $dto->type = NotifyMessage::EMAIL_TYPE;
            $dto->email = 'test@test.ru';
        }

        if ($type === NotifyMessage::TELEGRAM_TYPE) {
            $dto->type = NotifyMessage::TELEGRAM_TYPE;
            $dto->userId = 1234567890;
        }

        if ($type === NotifyMessage::VKONTAKTE_TYPE) {
            $dto->type = NotifyMessage::VKONTAKTE_TYPE;
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