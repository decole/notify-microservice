<?php


namespace App\Tests\unit\Application\Http\Api\SingleNotify\Service;


use App\Application\Http\Api\SingleNotify\Dto\MessageDto;
use App\Application\Http\Api\SingleNotify\Input\MessageInput;
use App\Application\Http\Api\SingleNotify\Service\SingleSendApiService;
use App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage;
use App\Tests\UnitTester;
use Faker\Factory;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

class SingleSendApiServiceCest
{
    private \Faker\Generator $faker;

    public function setUp()
    {
        $this->faker = Factory::create();
    }

    public function positiveCreateEmailInputDto(UnitTester $I): void
    {
        $service = $this->getService($I);
        $request = new Request([], [], [], [], [], [], $this->getEmailContent());
        $dto = $service->createInputDto($request);

        $I->assertInstanceOf(MessageInput::class, $dto);
        $I->assertEquals('email', $dto->type);
        $I->assertEquals('decole@rambler.ru', $dto->email);
        $I->assertEquals('tester2', $dto->message);
    }

    public function negativeCreateEmailInputDto(UnitTester $I): void
    {
        $service = $this->getService($I);
        $request = new Request([], [], [], [], [], [], '');

        try {
            $service->createInputDto($request);
        } catch (Throwable $exception) {}

        $I->assertEquals('Syntax error', $exception->getMessage());
    }

    public function positiveCreateMessageDtoByEmail(UnitTester $I): void
    {
        $type = NotifyMessage::EMAIL_TYPE;
        $dto = $this->getMessageInputDto($type);

        $service = $this->getService($I);
        $message = $service->createMessageDto($dto);

        $I->assertInstanceOf(MessageDto::class, $message);
        $I->assertEquals($type, $message->getType());
        $I->assertIsArray($message->getMessage());
    }

    public function negativeCreateMessageDtoByEmail(UnitTester $I): void
    {
        $dto = new MessageInput();
        $service = $this->getService($I);

        try {
            $service->createMessageDto($dto);
        } catch (Throwable $exception) {}

        $I->assertEquals('App\Application\Http\Api\SingleNotify\Input\MessageInput::getType(): Return value must be of type string, null returned', $exception->getMessage());
    }

    public function positiveGetPublishQueueMessageByEmail(UnitTester $I): void
    {
        $notify = new NotifyMessage(NotifyMessage::EMAIL_TYPE, ['test' => 'execute'], NotifyMessage::STATUS_IN_QUEUE);

        $service = $this->getService($I);
        $publication = $service->getPublishQueueMessage($notify);

        $I->assertEquals('{"messageId":"' . $notify->getId()->toString() . '"}', $publication);
    }

    public function negativeGetPublishQueueMessageByEmail(UnitTester $I): void
    {
        try {
            $service = $this->getService($I);
            $publication = $service->getPublishQueueMessage(null);
        } catch (Throwable $exception) {}

        $I->assertEquals(
            'App\Application\Http\Api\SingleNotify\Service\SingleSendApiService::getPublishQueueMessage(): Argument #1 ($message) must be of type App\Domain\Doctrine\NotifyMessage\Entity\NotifyMessage, null given, called in /var/www/tests/unit/Application/Http/Api/SingleNotify/Service/SingleSendApiServiceCest.php on line 88',
            $exception->getMessage()
        );
    }

    private function getEmailContent(): string
    {
        return '{"type":"email","email":"decole@rambler.ru","message":"tester2"}';
    }

    /**
     * @param UnitTester $I
     * @return SingleSendApiService
     */
    private function getService(UnitTester $I): SingleSendApiService
    {
        return $I->grabService(SingleSendApiService::class);
    }

    private function getMessageInputDto(string $notifyType): MessageInput
    {
        $dto = new MessageInput();
        $dto->type = $notifyType;
        $dto->message = $this->faker->text;

        if ($notifyType === NotifyMessage::EMAIL_TYPE) {
            $dto->email = $this->faker->email();
        }

        return $dto;
    }
}