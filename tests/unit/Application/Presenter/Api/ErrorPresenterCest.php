<?php


namespace App\Tests\unit\Application\Presenter\Api;


use App\Application\Presenter\Api\ErrorPresenter;
use App\Tests\UnitTester;
use PHPUnit\Util\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

class ErrorPresenterCest
{
    public function present(UnitTester $I): void
    {
        $notify = new Exception('test');
        $presenter = new ErrorPresenter($notify);
        $result = $presenter->present();
        $json = '{"result":false,"error":"An error occurred while executing the request","errorText":"test"}';

        $I->assertInstanceOf(JsonResponse::class, $result);
        $I->assertEquals($json, $result->getContent());
    }

    public function negativePresent(UnitTester $I): void
    {
        try {
            new ErrorPresenter(null);
        } catch (\Throwable $exception) {}

        $I->assertEquals(
            'App\Application\Presenter\Api\ErrorPresenter::__construct(): Argument #1 ($exception) must be of type Throwable, null given, called in /var/www/tests/unit/Application/Presenter/Api/ErrorPresenterCest.php on line 28',
            $exception->getMessage()
        );
    }
}