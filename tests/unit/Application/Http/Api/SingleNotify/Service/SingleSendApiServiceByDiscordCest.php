<?php


namespace App\Tests\unit\Application\Http\Api\SingleNotify\Service;


use App\Application\Http\Api\SingleNotify\Dto\MessageDto;
use App\Application\Http\Api\SingleNotify\Input\MessageInput;
use App\Application\Http\Api\SingleNotify\Service\SingleSendApiService;
use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Tests\UnitTester;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

class SingleSendApiServiceByDiscordCest
{
    private Generator $faker;

    public function setUp(): void
    {
        $this->faker = Factory::create();
    }

    public function positiveCreateVkontakteInputDto(UnitTester $I): void
    {
        $service = $this->getService($I);
        $request = new Request([], [], [], [], [], [], $this->getDiscordContent());
        $dto = $service->createInputDto($request);

        $I->assertInstanceOf(MessageInput::class, $dto);
        $I->assertEquals(NotifyMessage::DISCORD_TYPE, $dto->type);
        $I->assertEquals('test notification message', $dto->message);
    }

    public function negativeCreateDiscordInputDto(UnitTester $I): void
    {
        $service = $this->getService($I);
        $request = new Request([], [], [], [], [], [], '');

        try {
            $service->createInputDto($request);
        } catch (Throwable $exception) {}

        $I->assertEquals('Syntax error', $exception->getMessage());
    }

    public function positiveCreateMessageDto(UnitTester $I): void
    {
        $dto = $this->getMessageInputDto();

        $service = $this->getService($I);
        $message = $service->createMessageDto($dto);

        $I->assertInstanceOf(MessageDto::class, $message);
        $I->assertEquals(NotifyMessage::DISCORD_TYPE, $message->getType());
        $I->assertIsArray($message->getMessage());
    }

    public function negativeCreateMessageDto(UnitTester $I): void
    {
        $dto = new MessageInput();
        $service = $this->getService($I);

        try {
            $service->createMessageDto($dto);
        } catch (Throwable $exception) {}

        $I->assertEquals('App\Application\Http\Api\SingleNotify\Input\MessageInput::getType(): Return value must be of type string, null returned', $exception->getMessage());
    }

    public function positiveGetPublishQueueMessage(UnitTester $I): void
    {
        $notify = new NotifyMessage(NotifyMessage::DISCORD_TYPE, ['test' => 'execute'], NotifyMessage::STATUS_IN_QUEUE);
        $service = $this->getService($I);
        $publication = $service->getPublishQueueMessage($notify);

        $I->assertEquals('{"messageId":"' . $notify->getId()->toString() . '"}', $publication);
    }

    public function negativeGetPublishQueueMessage(UnitTester $I): void
    {
        try {
            $service = $this->getService($I);
            $service->getPublishQueueMessage(null);
        } catch (Throwable $exception) {}

        $I->assertEquals(true, str_contains(
            $exception->getMessage(),
            'App\Application\Http\Api\SingleNotify\Service\SingleSendApiService::getPublishQueueMessage(): Argument #1 ($message) must be of type App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage, null given, called in /var/www/tests/unit/Application/Http/Api/SingleNotify/Service/SingleSendApiServiceByDiscordCest.php'
        ));
    }

    private function getDiscordContent(): string
    {
        return '{"type":"discord","message":"test notification message"}';
    }

    private function getService(UnitTester $I): SingleSendApiService
    {
        return $I->grabService(SingleSendApiService::class);
    }

    private function getMessageInputDto(): MessageInput
    {
        $dto = new MessageInput();
        $dto->type = NotifyMessage::DISCORD_TYPE;
        $dto->message = $this->faker->text;

        return $dto;
    }
}