<?php


namespace App\Tests\unit\Application\Presenter\Api\Hello;


use App\Application\Presenter\Api\Hello\HelloApiPresenter;
use App\Tests\UnitTester;
use Symfony\Component\HttpFoundation\JsonResponse;

class HelloApiPresenterCest
{
    public function present(UnitTester $I): void
    {
        $json = '{"hello":"world"}';
        $presenter = new HelloApiPresenter();
        $result = $presenter->present();

        $I->assertInstanceOf(JsonResponse::class, $result);
        $I->assertEquals($json, $result->getContent());
    }
}