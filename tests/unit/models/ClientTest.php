<?php

namespace tests\models;

use app\models\Client;
use app\components\test\UnitTestHelper;
use app\tests\fixtures\ClientFixture;

class ClientTest extends \Codeception\Test\Unit
{
    use UnitTestHelper;

    public function fixtures ()
    {
        return [
            'clients' => ClientFixture::class,
        ];
    }

    public function testCreateWithoutRequiredFields ()
    {
        $client = new Client([
            'type'    => Client::TYPE_PROVIDER,
            'active'  => 1,
        ]);

        $this->validateModel($client, false);
    }

    public function testCreateWithWrongType()
    {
        $client = new Client([
            'name'    => 'Ирина',
            'surname' => 'Новикова',
            'type'    => 'Хз',
            'active'  => 1,
        ]);

        $this->validateModel($client, false);
    }

    public function testCreateSuccess ()
    {
        $client = new Client([
            'name'    => 'Ирина',
            'surname' => 'Новикова',
            'type'    => Client::TYPE_PROVIDER,
            'active'  => 1,
        ]);

        $this->validateModel($client, true);
    }
}
