<?php

namespace App\Tests\api\SingleNotify;

use App\Tests\ApiTester;

class SingleSendCest
{

    public function negativeWrongType(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/v1/send', [
            'type' => 'error',
            'message' => 'test',
        ]);
        $I->seeResponseIsJson();
        $I->canSeeResponseCodeIs(400);
        $I->seeResponseContainsJson([
            'error' => 'An error occurred while executing the request',
            'result' => false,
            'errorText' => 'Validation criteria by notify type not found.',
        ]);
    }


}
