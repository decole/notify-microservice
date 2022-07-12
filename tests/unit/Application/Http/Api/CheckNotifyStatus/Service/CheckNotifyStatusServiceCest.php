<?php


namespace App\Tests\unit\Application\Http\Api\CheckNotifyStatus\Service;


use App\Application\Http\Api\CheckNotifyStatus\Input\CheckStatusNotifyInput;
use App\Application\Http\Api\CheckNotifyStatus\Service\CheckNotifyStatusService;
use App\Tests\UnitTester;
use Ramsey\Uuid\Uuid;
use TypeError;

class CheckNotifyStatusServiceCest
{
    public function positiveCreateInput(UnitTester $I): void
    {
        $id = Uuid::uuid4()->toString();

        /** @var CheckNotifyStatusService $service */
        $service = $I->grabService(CheckNotifyStatusService::class);

        $input = $service->createInputDto($id);

        $I->assertInstanceOf(CheckStatusNotifyInput::class, $input);
        $I->assertEquals($id, $input->id);
    }

    public function negativeCreateEmptyInput(UnitTester $I): void
    {
        /** @var CheckNotifyStatusService $service */
        $service = $I->grabService(CheckNotifyStatusService::class);

        $id = null;

        $I->expectThrowable(
            new TypeError('App\Application\Http\Api\CheckNotifyStatus\Service\CheckNotifyStatusService::createInputDto(): Argument #1 ($id) must be of type string, null given, called in /var/www/tests/unit/Application/Http/Api/CheckNotifyStatus/Service/CheckNotifyStatusServiceCest.php on line 38'),
            function () use ($service, $id){
                $service->createInputDto($id);
            }
        );
    }

    public function negativeCreateAnotherTypeIdInput(UnitTester $I): void
    {
        $id = 'some text';

        /** @var CheckNotifyStatusService $service */
        $service = $I->grabService(CheckNotifyStatusService::class);

        $input = $service->createInputDto($id);

        $I->assertInstanceOf(CheckStatusNotifyInput::class, $input);
        $I->assertEquals($id, $input->id);
    }
}