<?php

use ApiTester;

class ClientCest
{
    public function testGetUnexistingClientById (ApiTester $I)
    {
        $I->sendGET('index.php?r=client%2Fget-by-id&clientId=100500');
        $I->canSeeResponseCodeIs(404);
        $I->canSeeResponseContainsJson('{"success": false}');
        $I->canSeeResponseContainsJson('{"error": "Клиент с таким id не найден"}');
    }

    public function testGetById (ApiTester $I)
    {
        $I->sendGET('index.php?r=client%2Fget-by-id&clientId=1');
        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseContainsJson('{"success": true}');
        $I->canSeeResponseContainsJson('{"data": {"id": 1}}');
    }
}