<?php


namespace App\Tests\unit\Application\Service;


use App\Application\Exception\NotFoundEntityException;
use App\Application\Http\Api\SingleNotify\Input\MessageInput;
use App\Application\Service\ValidationService;
use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Tests\UnitTester;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Validator\ConstraintViolationList;

class ValidationServiceCest
{
    private ValidationService $service;
    private Generator $faker;

    public function setUp(UnitTester $I): void
    {
        $this->service = $I->grabService(ValidationService::class);
        $this->faker = Factory::create();
    }

    public function positiveEmailValidation(UnitTester $I): void
    {
        $list = $this->service->validate($this->getEmailDto());

        $I->assertInstanceOf(ConstraintViolationList::class, $list);
    }

    public function positiveTelegramValidation(UnitTester $I): void
    {
        $list = $this->service->validate($this->getTelegramDto());

        $I->assertInstanceOf(ConstraintViolationList::class, $list);
    }

    public function negativeValidation(UnitTester $I): void
    {
        $I->expectThrowable(
            new NotFoundEntityException('Validation criteria by notify type not found.'),
            function () {
                $this->service->validate($this->getWrongDto());
            }
        );
    }

    private function getEmailDto(): MessageInput
    {
        $dto = new MessageInput();
        $dto->type = NotifyMessage::EMAIL_TYPE;
        $dto->message = 'test';
        $dto->email = 'test@test.ru';

        return $dto;
    }

    private function getTelegramDto(): MessageInput
    {
        $dto = new MessageInput();
        $dto->type = NotifyMessage::TELEGRAM_TYPE;
        $dto->userId = $this->faker->biasedNumberBetween(10000000, 99999999999);
        $dto->message = 'test telegram';

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