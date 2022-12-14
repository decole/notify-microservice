<?php


namespace App\Tests\functional\Infrastructure\Doctrine\Service;


use App\Application\Http\Api\SingleNotify\Dto\MessageDto;
use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Domain\Doctrine\NotifyMessage\Enum\NotifyStatusEnum;
use App\Domain\Doctrine\NotifyMessage\Enum\NotifyTypeEnum;
use App\Infrastructure\Doctrine\Service\NotifyMessageService;
use App\Tests\FunctionalTester;
use Ramsey\Uuid\Uuid;
use Throwable;

class NotifyMessageServiceCest
{
    private NotifyMessageService $service;

    public function setUp(FunctionalTester $I): void
    {
        $this->service = $I->grabService(NotifyMessageService::class);
    }

    public function createEmail(FunctionalTester $I): void
    {
        $text = ['rom' => 'test text'];
        $dto = new MessageDto(NotifyTypeEnum::EMAIL->value, $text);
        $savedEntity = $this->service->create($dto);

        $I->assertInstanceOf(NotifyMessage::class, $savedEntity);
        $I->assertEquals($text, $savedEntity->getBody());
        $I->assertEquals(NotifyTypeEnum::EMAIL->value, $savedEntity->getType());
        $I->assertEquals(NotifyStatusEnum::IN_QUEUE->value, $savedEntity->getStatus());
        $I->assertEquals('in queue', $savedEntity->getTextStatus());
    }

    public function createTelegram(FunctionalTester $I): void
    {
        $text = ['rom' => 'test text'];
        $dto = new MessageDto(NotifyTypeEnum::TELEGRAM->value, $text);
        $savedEntity = $this->service->create($dto);

        $I->assertInstanceOf(NotifyMessage::class, $savedEntity);
        $I->assertEquals($text, $savedEntity->getBody());
        $I->assertEquals(NotifyTypeEnum::TELEGRAM->value, $savedEntity->getType());
        $I->assertEquals(NotifyStatusEnum::IN_QUEUE->value, $savedEntity->getStatus());
        $I->assertEquals('in queue', $savedEntity->getTextStatus());
    }

    public function createVkontakte(FunctionalTester $I): void
    {
        $text = ['rom' => 'test text'];
        $dto = new MessageDto(NotifyTypeEnum::VKONTAKTE->value, $text);
        $savedEntity = $this->service->create($dto);

        $I->assertInstanceOf(NotifyMessage::class, $savedEntity);
        $I->assertEquals($text, $savedEntity->getBody());
        $I->assertEquals(NotifyTypeEnum::VKONTAKTE->value, $savedEntity->getType());
        $I->assertEquals(NotifyStatusEnum::IN_QUEUE->value, $savedEntity->getStatus());
        $I->assertEquals('in queue', $savedEntity->getTextStatus());
    }

    public function createSlack(FunctionalTester $I): void
    {
        $text = ['rom' => 'test text'];
        $dto = new MessageDto(NotifyTypeEnum::SLACK->value, $text);
        $savedEntity = $this->service->create($dto);

        $I->assertInstanceOf(NotifyMessage::class, $savedEntity);
        $I->assertEquals($text, $savedEntity->getBody());
        $I->assertEquals(NotifyTypeEnum::SLACK->value, $savedEntity->getType());
        $I->assertEquals(NotifyStatusEnum::IN_QUEUE->value, $savedEntity->getStatus());
        $I->assertEquals('in queue', $savedEntity->getTextStatus());
    }

    public function createSms(FunctionalTester $I): void
    {
        $text = ['rom' => 'test text'];
        $dto = new MessageDto(NotifyTypeEnum::SMS->value, $text);
        $savedEntity = $this->service->create($dto);

        $I->assertInstanceOf(NotifyMessage::class, $savedEntity);
        $I->assertEquals($text, $savedEntity->getBody());
        $I->assertEquals(NotifyTypeEnum::SMS->value, $savedEntity->getType());
        $I->assertEquals(NotifyStatusEnum::IN_QUEUE->value, $savedEntity->getStatus());
        $I->assertEquals('in queue', $savedEntity->getTextStatus());
    }

    public function negativeCreate(FunctionalTester $I): void
    {
        try {
            $this->service->create(null);
        } catch (Throwable $exception) {}

        $I->assertEquals(true, str_contains(
            $exception->getMessage(),
            'App\Infrastructure\Doctrine\Service\NotifyMessageService::create(): Argument #1 ($dto) must be of type App\Application\Http\Api\SingleNotify\Dto\MessageDto, null given, called in /var/www/tests/functional/Infrastructure/Doctrine/Service/NotifyMessageServiceCest.php'
        ));
    }

    public function find(FunctionalTester $I): void
    {
        $text = ['lol' => 'kek'];
        $dto = new MessageDto(NotifyTypeEnum::EMAIL->value, $text);
        $savedEntity = $this->service->create($dto);
        $foundEntity = $this->service->find($savedEntity->getId()->toString());

        $I->assertInstanceOf(NotifyMessage::class, $foundEntity);
        $I->assertEquals($text, $foundEntity->getBody());
        $I->assertEquals(NotifyTypeEnum::EMAIL->value, $foundEntity->getType());
        $I->assertEquals(NotifyStatusEnum::IN_QUEUE->value, $foundEntity->getStatus());
    }

    public function negativeFind(FunctionalTester $I): void
    {
        $id = Uuid::uuid4()->toString();
        $foundEntity = $this->service->find($id);

        $I->assertEquals(null, $foundEntity);
    }

    public function update(FunctionalTester $I): void
    {
        $text = ['one' => 'two'];
        $dto = new MessageDto(NotifyTypeEnum::EMAIL->value, $text);
        $savedEntity = $this->service->create($dto);

        $I->assertInstanceOf(NotifyMessage::class, $savedEntity);
        $I->assertEquals($text, $savedEntity->getBody());
        $I->assertEquals(NotifyTypeEnum::EMAIL->value, $savedEntity->getType());
        $I->assertEquals(NotifyStatusEnum::IN_QUEUE->value, $savedEntity->getStatus());
        $I->assertEquals(null, $savedEntity->getUpdatedAt());
        $I->assertNotNull($savedEntity->getCreatedAt());

        $savedEntity->setStatus(NotifyStatusEnum::DONE->value);
        $savedEntity->setUpdatedAt();

        $this->service->update($savedEntity);

        $foundEntity = $this->service->find($savedEntity->getId()->toString());

        $I->assertInstanceOf(NotifyMessage::class, $foundEntity);
        $I->assertEquals(NotifyTypeEnum::EMAIL->value, $foundEntity->getType());
        $I->assertEquals(NotifyStatusEnum::DONE->value, $foundEntity->getStatus());
        $I->assertNotNull($savedEntity->getCreatedAt());
        $I->assertNotNull($savedEntity->getUpdatedAt());
    }

    public function negativeUpdate(FunctionalTester $I): void
    {
        try {
            $this->service->update(null);
        } catch (Throwable $exception) {}

        $I->assertEquals(true, str_contains(
            $exception->getMessage(),
            'App\Infrastructure\Doctrine\Service\NotifyMessageService::update(): Argument #1 ($notify) must be of type App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage, null given, called in /var/www/tests/functional/Infrastructure/Doctrine/Service/NotifyMessageServiceCest.php'
        ));
    }

    public function updateStatus(FunctionalTester $I): void
    {
        $text = ['one' => 'two'];
        $dto = new MessageDto(NotifyTypeEnum::EMAIL->value, $text);
        $savedEntity = $this->service->create($dto);

        $I->assertInstanceOf(NotifyMessage::class, $savedEntity);
        $I->assertEquals($text, $savedEntity->getBody());
        $I->assertEquals(NotifyTypeEnum::EMAIL->value, $savedEntity->getType());
        $I->assertEquals(NotifyStatusEnum::IN_QUEUE->value, $savedEntity->getStatus());
        $I->assertEquals(null, $savedEntity->getUpdatedAt());
        $I->assertNotNull($savedEntity->getCreatedAt());

        $savedEntity->setStatus(NotifyStatusEnum::DONE->value);
        $savedEntity->setUpdatedAt();

        $this->service->updateStatus($savedEntity, NotifyStatusEnum::ERROR->value);

        $foundEntity = $this->service->find($savedEntity->getId()->toString());

        $I->assertInstanceOf(NotifyMessage::class, $foundEntity);
        $I->assertEquals(NotifyTypeEnum::EMAIL->value, $foundEntity->getType());
        $I->assertEquals(NotifyStatusEnum::ERROR->value, $foundEntity->getStatus());
        $I->assertNotNull($savedEntity->getCreatedAt());
        $I->assertNotNull($savedEntity->getUpdatedAt());
    }

    public function negativeUpdateStatusEmptyEntity(FunctionalTester $I): void
    {
        try {
            $this->service->updateStatus(null, NotifyStatusEnum::ERROR->value);
        } catch (Throwable $exception) {}

        $I->assertEquals(true, str_contains(
            $exception->getMessage(),
            'App\Infrastructure\Doctrine\Service\NotifyMessageService::updateStatus(): Argument #1 ($message) must be of type App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage, null given, called in /var/www/tests/functional/Infrastructure/Doctrine/Service/NotifyMessageServiceCest.php'
        ));
    }

    public function negativeUpdateStatusWrongStatus(FunctionalTester $I): void
    {
        try {
            $text = ['one' => 'two'];
            $dto = new MessageDto(NotifyTypeEnum::EMAIL->value, $text);
            $savedEntity = $this->service->create($dto);

            $this->service->updateStatus($savedEntity, 99999);

        } catch (Throwable $exception) {}

        $I->assertEquals(
            'Expected one of: 0, 1, 2, 3. Got: 99999',
            $exception->getMessage()
        );
    }
}