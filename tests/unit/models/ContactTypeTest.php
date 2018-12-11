<?php

namespace tests\models;

use app\models\Client;
use app\components\test\UnitTestHelper;
use app\models\ContactType;
use app\tests\fixtures\ClientFixture;
use app\tests\fixtures\ContactTypeFixture;

class ContactTypeTest extends \Codeception\Test\Unit
{
    use UnitTestHelper;

    public function fixtures ()
    {
        return [
            'contact-types' => ContactTypeFixture::class,
        ];
    }

    /**
     * >_<
     */
    public function _before ()
    {
        ContactType::deleteAll('1 = 1');
    }

    public function testCreateWithoutRequiredFields ()
    {
        $client = new ContactType();

        $this->validateModel($client, false);
        $this->assertFalse($client->save());
    }

    public function testCreateWithLongType ()
    {
        $client = new ContactType(['type' => str_repeat('бла-', 50)]);

        $this->validateModel($client, false, ['type']);
        $this->assertFalse($client->save());
    }

    public function testCreateSuccess ()
    {
        $client = new ContactType(['type' => 'E-mail']);

        $this->validateModel($client, true);
        $this->assertTrue($client->save());
    }

    public function testGetAllTypesOnEmpty()
    {
        $types = ContactType::getAllTypesAsArray();
        $this->assertEmpty($types);
    }

    public function testGetAllTypes()
    {
        (new ContactType(['type' => 'E-mail']))->save();

        $types = ContactType::getAllTypesAsArray();
        $this->assertNotEmpty($types);
    }
}
