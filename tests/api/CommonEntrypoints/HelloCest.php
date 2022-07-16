<?php


namespace App\Tests\api\CommonEntrypoints;


use App\Tests\ApiTester;

class HelloCest
{
    public function helloEntrypoint(ApiTester $I)
    {
        $I->sendGet('/');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'hello' => 'world',
        ]);
    }
}