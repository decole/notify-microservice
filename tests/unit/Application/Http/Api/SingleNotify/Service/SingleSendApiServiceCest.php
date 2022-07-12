<?php


namespace App\Tests\unit\Application\Http\Api\SingleNotify\Service;


use App\Application\Http\Api\SingleNotify\Input\MessageInput;
use App\Application\Http\Api\SingleNotify\Service\SingleSendApiService;
use App\Tests\UnitTester;
use JsonException;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

class SingleSendApiServiceCest
{
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
        } catch (Throwable $exception) {
            $I->assertEquals('Syntax error', $exception->getMessage());
        }
    }

    // todo доделать оставшиеся методы

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
}